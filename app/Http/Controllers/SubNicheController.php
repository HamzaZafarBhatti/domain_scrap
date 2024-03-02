<?php

namespace App\Http\Controllers;

use App\Imports\SubNicheImport;
use App\Models\Niche;
use App\Models\SubNiche;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class SubNicheController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $records = SubNiche::all();
        if (request()->has('niche')) {
            $records = SubNiche::where('niche_id', request()->niche)->get();
            return response()->json($records, 200);
        }
        $niche = Niche::all();
        return view('admin.sub-niche.index', compact('records', 'niche'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'name' => 'required',
            'niche_id' => 'required'
        ]);
        try {
            SubNiche::create($data);
            return back()->with('success', 'SubNiche added successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function change_status(SubNiche $sub_niche)
    {
        try {
            if ($sub_niche->is_active) {
                $sub_niche->update(['is_active' => false]);
                $message = 'SubNiche status changed to inactive';
            } else {
                $sub_niche->update(['is_active' => true]);
                $message = 'SubNiche status changed to active';
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
    public function destroy(SubNiche $sub_niche)
    {
        //
        try {
            $sub_niche->delete();
            return back()->with('success', 'SubNiche deleted successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xls,xlsx,application/vnd.ms-excel,text/plain,text/csv,application/csv,application/excel,application/vnd.msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'niche' => 'required'
        ]);
        try {
            $path = $request->file('file');
            $path = $path->storeAs('public', $path->getClientOriginalName());
            $path = storage_path('app/' . $path);
            Excel::import(new SubNicheImport($request->niche), $path);
            return back()->with('success', 'SubNiches imported successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
}
