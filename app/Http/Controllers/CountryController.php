<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CountryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $countries = Country::all();
        return view('admin.countries.index', compact('countries'));
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
            Country::create($data);
            return back()->with('success', 'Country added successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function change_status(Country $country)
    {
        //
        try {
            if ($country->is_active) {
                $country->update(['is_active' => false]);
                $message = 'Country status changed to inactive';
            } else {
                $country->update(['is_active' => true]);
                $message = 'Country status changed to active';
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
    public function destroy(Country $country)
    {
        try {
            $country->delete();
            return back()->with('success', 'Country deleted successfully');
        } catch (\Throwable $th) {
            Log::info($th->getMessage());
            return back()->with('error', 'Something went wrong!');
        }
    }
}
