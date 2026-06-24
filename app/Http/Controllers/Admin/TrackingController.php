<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserTracking;

class TrackingController extends Controller
{
    public function index()
    {
        $trackings = UserTracking::latest()->paginate(100);
        return view('admin.tracking.index', compact('trackings'));
    }
}
