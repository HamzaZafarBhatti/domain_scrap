<?php

namespace App\Http\Controllers;

use App\Models\Niche;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NicheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $records = Niche::all();
        return view('admin.niche.index', compact('records'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'name' => 'required'
        ]);
        try {
            Niche::create($data);
            return back()->with('success', 'Niche added successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function change_status(Niche $niche)
    {
        //
        try {
            if ($niche->is_active) {
                $niche->update(['is_active' => false]);
                $message = 'Niche status changed to inactive';
            } else {
                $niche->update(['is_active' => true]);
                $message = 'Niche status changed to active';
            }
            return back()->with('success', $message);
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Niche $niche)
    {
        //
        try {
            $niche->delete();
            return back()->with('success', 'Niche deleted successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
}
