<?php

namespace App\Http\Controllers;

use App\Imports\CityImport;
use App\Models\City;
use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;

class CityController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $cities = City::with('country')->get();
        $countries = Country::where('is_active', 1)->get();
        return view('admin.cities.index', compact('cities', 'countries'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $data = $request->validate([
            'name' => 'required',
            'country_id' => 'required',
        ]);
        try {
            City::create($data);
            return back()->with('success', 'City added successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function change_status(City $city)
    {
        //
        try {
            if ($city->is_active) {
                $city->update(['is_active' => false]);
                $message = 'City status changed to inactive';
            } else {
                $city->update(['is_active' => true]);
                $message = 'City status changed to active';
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
    public function destroy(City $city)
    {
        try {
            $city->delete();
            return back()->with('success', 'City deleted successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:csv,txt,xls,xlsx,application/vnd.ms-excel,text/plain,text/csv,application/csv,application/excel,application/vnd.msexcel,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'country_id' => 'required'
        ]);
        try {
            $path = $request->file('file');
            $path = $path->storeAs('public', $path->getClientOriginalName());
            $path = storage_path('app/' . $path);
            Excel::import(new CityImport($request->country_id), $path);
            return back()->with('success', 'Cities imported successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            dd($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    public function get_by_country(Request $request)
    {
        $cities = City::select('name', 'id')->when(!empty($request->country_id), function ($q) use ($request) {
            $q->where('country_id', $request->country_id);
        })->get();
        return response()->json($cities);
    }
}
