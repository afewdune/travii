<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\FishRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class FishRecordController extends Controller
{
    public function index()
    {
        Log::debug('Index method called', ['user_id' => Auth::id()]);

        try {
            $fishRecords = FishRecord::with('fish')
                ->where('FisherID', Auth::id())
                ->get();

            Log::debug('Fish records retrieved', ['fishRecords' => $fishRecords]);

            return response()->json($fishRecords);
        } catch (\Exception $e) {
            Log::error('Error retrieving fish records', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json(['error' => 'Internal Server Error'], 500);
        }
    }

    public function store(Request $request)
    {
        Log::debug('Store method called', ['request' => $request->all()]);

        $request->validate([
            'fish_id' => 'required|integer|exists:fish,FishID',
        ]);

        $fishRecord = new FishRecord();
        $fishRecord->FishID = $request->fish_id;
        $fishRecord->FisherID = Auth::id(); // ดึง FisherID จาก session ของผู้ใช้ที่ล็อกอินอยู่
        $fishRecord->save();

        Log::debug('Fish record saved', ['fishRecord' => $fishRecord]);

        return response()->json(['message' => 'Fish record saved successfully'], 201);
    }
}
