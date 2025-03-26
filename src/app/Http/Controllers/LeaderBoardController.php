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
        $leaderboard = FishRecord::join('Fish', 'FishRecord.FishID', '=', 'Fish.FishID')
            ->select('FishRecord.FisherID', DB::raw('SUM(Fish.FishWorth) as total_worth'))
            ->groupBy('FishRecord.FisherID')
            ->orderBy('total_worth', 'desc')
            ->take(5)
            ->get();

        // ดึงชื่อผู้เล่นจากตาราง users
        $leaderboard = $leaderboard->map(function ($record) {
            $user = User::find($record->FisherID);
            return [
                'name' => $user->username,
                'total_worth' => $record->total_worth
            ];
        });

        return response()->json($leaderboard);
    }
}