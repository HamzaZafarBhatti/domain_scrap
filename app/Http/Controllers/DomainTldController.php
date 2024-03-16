<?php

namespace App\Http\Controllers;

use App\Imports\DomainTldImport;
use App\Models\DomainTld;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;


class DomainTldController extends Controller
{
    public function index()
    {
        //
        $records = DomainTld::all();
        return view('admin.domain-tld.index', compact('records'));
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

        if (substr($data['name'], 0, 1) !== '.') {
            $data['name'] = '.' . $data['name'];
        }
        try {
            DomainTld::updateOrCreate($data,$data);
            return back()->with('success', 'DomainTld added successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function change_status(DomainTld $domain_tld)
    {
        //
        try {
            if ($domain_tld->is_active) {
                $domain_tld->update(['is_active' => false]);
                $message = 'DomainTld status changed to inactive';
            } else {
                $domain_tld->update(['is_active' => true]);
                $message = 'DomainTld status changed to active';
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
    public function destroy(DomainTld $domain_tld)
    {
        //
        try {
            $domain_tld->delete();
            return back()->with('success', 'DomainTld deleted successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xls,xlsx,csv'
        ]);

        try {
            $path = $request->file('file');
            $path = $path->storeAs('public', $path->getClientOriginalName());
            $path = storage_path('app/' . $path);
            Excel::import(new DomainTldImport, $path);
            return back()->with('success', 'DomainTlds imported successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
}
