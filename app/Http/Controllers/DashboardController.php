<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function dashboard()
    {
        // Obtain the authenticated user
        $user = auth()->user();
        // Get all assigned job requests for the user and sort by created_at in descending order
        $jobRequests = $user->jobRequests()
            ->orderBy('created_at', 'desc')
            ->get();
        // Return the dashboard view with the job requests
        return view('dashboard', compact('jobRequests'));
    }
}
