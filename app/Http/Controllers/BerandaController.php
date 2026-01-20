<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Member;
use App\Models\Activity;

class BerandaController extends Controller
{
    public function index()
    {
        // Ambil data activities dari database
        $activities = Activity::active()->ordered()->get();

        // Ambil data members dari database
        $members = Member::active()->ordered()->get();

        return view('beranda', compact('activities', 'members'));
    }
}
