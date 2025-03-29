<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Rod;
use App\Models\RodPurchase;

class SelectedRodController extends Controller
{
    public function getUserRods()
    {
        $user = Auth::user();

        if (!$user) {
            return response()->json(['error' => 'User not authenticated'], 401);
        }

        $selectedRod = $user->rod;

        $ownedRods = RodPurchase::where('user_id', $user->id)
            ->with('rod') 
            ->get()
            ->map(function ($purchase) {
                return $purchase->rod;
            });

        return response()->json([
            'selectedRod' => $selectedRod,
            'ownedRods' => $ownedRods,
        ]);
    }

    public function getSelectedRod()
    {
        $user = Auth::user();

        if (!$user || !$user->rod_id) {
            return response()->json(['error' => 'No rod selected'], 404);
        }

        $rod = $user->rod; 
        return response()->json($rod);
    }
}