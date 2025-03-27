<?php

namespace App\Http\Controllers;

use App\Models\Rod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class RodController extends Controller
{
    public function index()
    {
        $rods = Rod::paginate(10);

        return view('admin.rods.index', compact('rods'));
    }

    public function create()
    {
        return view('admin.rods.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'chance_common' => 'required|numeric',
            'chance_rare' => 'required|numeric',
            'chance_sr' => 'required|numeric',
            'chance_ssr' => 'required|numeric',
            'chance_nft' => 'required|numeric',
            'chance_special' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('rods', 'public');
        }

        Rod::create($data);

        return redirect()->route('admin.rods.index')->with('success', 'Rod created successfully.');
    }

    public function edit(Rod $rod)
    {
        return view('admin.rods.edit', compact('rod'));
    }

    public function update(Request $request, Rod $rod)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer',
            'chance_common' => 'required|numeric',
            'chance_rare' => 'required|numeric',
            'chance_sr' => 'required|numeric',
            'chance_ssr' => 'required|numeric',
            'chance_nft' => 'required|numeric',
            'chance_special' => 'required|numeric',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($rod->image) {
                Storage::disk('public')->delete($rod->image);
            }
            $data['image'] = $request->file('image')->store('rods', 'public');
        }

        $rod->update($data);

        return redirect()->route('admin.rods.index')->with('success', 'Rod updated successfully.');
    }

    public function destroy(Rod $rod)
    {
        if ($rod->image) {
            Storage::disk('public')->delete($rod->image);
        }
        $rod->delete();

        return redirect()->route('admin.rods.index')->with('success', 'Rod deleted successfully.');
    }
}
