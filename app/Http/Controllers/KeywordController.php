<?php

namespace App\Http\Controllers;

use App\Imports\AdditionalKeywordImport;
use App\Models\Keyword;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class KeywordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $keywords = Keyword::all();
        return view('admin.keywords.index', compact('keywords'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'name' => 'required',
        ]);
        try {
            Keyword::create($data);
            return back()->with('success', 'Keyword added successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
    public function change_status(Keyword $keyword)
    {
        //
        try {
            if ($keyword->is_active) {
                $keyword->update(['is_active' => false]);
                $message = 'Keyword status changed to inactive';
            } else {
                $keyword->update(['is_active' => true]);
                $message = 'Keyword status changed to active';
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
    public function destroy(Keyword $keyword)
    {
        try {
            $keyword->delete();
            return back()->with('success', 'Keyword deleted successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
    public function import (Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);
        try {
            $path = $request->file('file');
            $path = $path->storeAs('public', $path->getClientOriginalName());
            $path = storage_path('app/' . $path);
            Excel::import(new AdditionalKeywordImport, $path);
            return back()->with('success', 'Cities imported successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
}
