<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\FishRecord;
use Illuminate\Support\Facades\DB;

class LeaderBoardController extends Controller
{
    public function index()
    {
        $leaderboard = FishRecord::select('FisherID', DB::raw('SUM(FishWorth) as total_worth'))
            ->groupBy('FisherID')
            ->orderBy('total_worth', 'desc')
            ->take(5)
            ->get();

        // ดึงชื่อผู้เล่นจากตาราง users
        $leaderboard = $leaderboard->map(function ($record) {
            $user = User::find($record->FisherID);
            return [
                'name' => $user->name,
                'total_worth' => $record->total_worth
            ];
        });

        return response()->json($leaderboard);
    }
}