<?php

namespace App\Http\Controllers;

use App\Models\JobDone;
use Illuminate\Http\Request;

class JobDoneController extends Controller
{
    public function index ()
    {
        $jobs = JobDone::all();
        return view('admin.job-done.index',compact('jobs'));
    }
}
