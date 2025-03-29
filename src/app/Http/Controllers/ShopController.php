<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rod;
use App\Models\RodPurchase;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    public function index()
    {
        $rods = Rod::paginate(10);
        
        return view('shop', ['rods' => $rods]);
    }

    public function buyRod(Request $request)
    {
        $user = Auth::user();
        $rodId = $request->input('rod_id');

        // ตรวจสอบว่าเบ็ดตกปลามีอยู่จริง
        $rod = Rod::find($rodId);
        if (!$rod) {
            return response()->json(['error' => 'Rod not found'], 404);
        }

        // ตรวจสอบว่า user มีเงินเพียงพอ
        if ($user->coin < $rod->price) {
            return response()->json(['error' => 'Not enough coins'], 400);
        }

        // หักเงินและบันทึกการซื้อ
        $user->coin -= $rod->price;
        $user->rod_id = $rodId; // ตั้งค่าเบ็ดตกปลาใหม่
        $user->save();

        // บันทึกการซื้อในตาราง rod_purchases
        RodPurchase::create([
            'user_id' => $user->id,
            'rod_id' => $rodId,
        ]);

        return response()->json(['message' => 'Rod purchased successfully', 'user' => $user]);
    }

    public function switchRod(Request $request)
    {
        // $current_rod = $request->input('rod_id');

        $all_user_rod = RodPurchase::join('rods', 'rods.id', '=', 'rod_purchases.rod_id')
                                    ->where('rod_purchases.user_id', Auth::user()->id)
                                    ->get();

        return $all_user_rod;
    }
}
