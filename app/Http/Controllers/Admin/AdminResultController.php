<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DrawResult;
use Illuminate\Http\Request;

class AdminResultController extends Controller
{
    // List draw results
    public function index(Request $request)
    {
        $query = DrawResult::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function($q) use ($search) {
                $q->where('lottery_name', 'like', "%{$search}%")
                  ->orWhere('draw_number', 'like', "%{$search}%")
                  ->orWhere('winning_number', 'like', "%{$search}%");
            });
        }

        $results = $query->orderBy('draw_date', 'desc')->paginate(15)->withQueryString();

        return view('admin.results.index', compact('results'));
    }

    // Store new result
    public function store(Request $request)
    {
        $request->validate([
            'draw_date' => 'required|date',
            'lottery_name' => 'required|string|max:100',
            'draw_number' => 'required|string|max:50',
            'winning_number' => 'required|string|max:50',
        ]);

        DrawResult::create($request->all());

        return redirect()->route('admin.results.index')->with('success', 'Draw result added successfully.');
    }

    // Update result
    public function update(Request $request, $id)
    {
        $result = DrawResult::findOrFail($id);

        $request->validate([
            'draw_date' => 'required|date',
            'lottery_name' => 'required|string|max:100',
            'draw_number' => 'required|string|max:50',
            'winning_number' => 'required|string|max:50',
        ]);

        $result->update($request->all());

        return redirect()->route('admin.results.index')->with('success', 'Draw result updated successfully.');
    }

    // Delete result
    public function destroy($id)
    {
        $result = DrawResult::findOrFail($id);
        $result->delete();

        return redirect()->route('admin.results.index')->with('success', 'Draw result deleted successfully.');
    }
}
