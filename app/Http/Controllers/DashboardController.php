<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Obtain the authenticated user
        $user = auth()->user();
        // Get all assigned job requests for the user
        $jobRequests = $user->jobRequests()->with('attachments')->get();
        return view('dashboard', compact('jobRequests'));
    }
}
