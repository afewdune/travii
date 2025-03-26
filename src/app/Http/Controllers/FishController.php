<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Fish;
use App\Models\FishRecord;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\User;

class FishController extends Controller
{
    public function index()
    {
        try {
            $fishRecords = Fish::paginate(10);
            return response()->json($fishRecords);
        } catch (\Exception $e) {
            \Log::error('Error fetching fish records: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function create()
    {
        return view('admin.add-fish');
    }

    public function store(Request $request)
    {
        $request->validate([
            'FishName' => 'required|string|max:255',
            'FishRarity' => 'required|string',
            'FishWorth' => 'required|numeric',
            'FishTokenWorth' => 'required|numeric',
            'FishImage' => 'required|image|max:25600', // 25MB
        ]);

        $path = $request->file('FishImage')->store('fish_images', 'public');

        Fish::create([
            'FishName' => $request->FishName,
            'FishRarity' => $request->FishRarity,
            'FishWorth' => $request->FishWorth,
            'FishTokenWorth' => $request->FishTokenWorth,
            'FishImage' => $path,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Fish added successfully');
    }

    public function edit($id)
    {
        $fish = Fish::findOrFail($id); // ตรวจสอบการอ้างอิงคอลัมน์หลัก
        return view('admin.edit-fish', compact('fish'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'FishName' => 'required|string|max:255',
            'FishRarity' => 'required|string',
            'FishWorth' => 'required|numeric',
            'FishTokenWorth' => 'required|numeric',
            'FishImage' => 'nullable|image|max:25600', // 25MB
        ]);

        $fish = Fish::findOrFail($id); // ตรวจสอบการอ้างอิงคอลัมน์หลัก

        if ($request->hasFile('FishImage')) {
            // ลบรูปภาพเก่า
            if ($fish->FishImage) {
                Storage::disk('public')->delete($fish->FishImage);
            }
            // อัปโหลดรูปภาพใหม่
            $path = $request->file('FishImage')->store('fish_images', 'public');
            $fish->FishImage = $path;
        }

        $fish->update([
            'FishName' => $request->FishName,
            'FishRarity' => $request->FishRarity,
            'FishWorth' => $request->FishWorth,
            'FishTokenWorth' => $request->FishTokenWorth,
        ]);

        return redirect()->route('admin.dashboard')->with('success', 'Fish updated successfully');
    }

    public function destroy($id)
    {
        $fish = Fish::findOrFail($id); // ตรวจสอบการอ้างอิงคอลัมน์หลัก
        if ($fish->FishImage) {
            Storage::disk('public')->delete($fish->FishImage);
        }
        $fish->delete();

        return redirect()->route('admin.dashboard')->with('success', 'Fish deleted successfully');
    }

    public function getRandomFish()
    {
        $fish = Fish::inRandomOrder()->first();
        return response()->json($fish);
    }

    public function recordCatch(Request $request)
    {
        $request->validate([
            'fish_id' => 'required|integer|exists:fish,FishID',
        ]);

        $fishRecord = new FishRecord();
        $fishRecord->FishID = $request->fish_id;
        $fishRecord->FisherID = Auth::id(); // ดึง FisherID จาก session ของผู้ใช้ที่ล็อกอินอยู่
        $fishRecord->save();

        return response()->json(['message' => 'Fish record saved successfully'], 201);
    }

    public function getAdminData()
    {
        try {
            $adminData = Admin::all();
            $fishList = Fish::paginate(10);
            return response()->json(['adminData' => $adminData, 'fishList' => $fishList]);
        } catch (\Exception $e) {
            \Log::error('Error fetching admin data: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function sellFish(Request $request)
    {
        $user = Auth::user();
        $fishId = $request->input('fishId');
        $count = $request->input('count');

        // ค้นหา FishRecord ล่าสุดของผู้ใช้ที่มี FishID ตรงกับที่ส่งมา
        $fishRecord = FishRecord::where('FishID', $fishId)
                                ->where('FisherID', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->first();

        if ($fishRecord && $fishRecord->count >= $count) {
            $fishRecord->count -= $count;
            $fishRecord->save();

            $user->coin += $fishRecord->fish->FishWorth * $count;
            $user->save();

            return response()->json(['user' => $user]);
        }

        return response()->json(['error' => 'Fish not found or insufficient count'], 404);
    }
}