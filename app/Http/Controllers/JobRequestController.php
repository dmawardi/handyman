<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobRequestController extends Controller
{
    public function index()
    {
        // Fetch all job requests for the authenticated user
        $user = auth()->user();
        $jobRequests = \App\Models\JobRequest::where('user_id', $user->id)->get();
        return view('job_requests.index', compact('jobRequests'));
    }

    public function create()
    {
        return view('job_requests.create');
    }

    public function store(Request $request)
    {
        // Validate the request data
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'job_type' => 'required|string|in:Plumbing,Electrical,Painting,Appliance Repair,Outdoor/Garden,Installations,Cleaning/Maintenance,Other',
            'urgency_level' => 'required|string|in:Low - Within 2 weeks,Medium - Within 1 week,High - Within 48 hours,Emergency - Same day',
            'job_budget' => 'nullable|numeric|min:0',
            'job_description' => 'required|string',
        ]);

        // Find the user by email
        $user = \App\Models\User::where('email', $validated['contact_email'])->first();
        Log::info("User lookup", [
            'email' => $validated['contact_email'],
            'user_found' => $user ? true : false
        ]);
        if (!$user) {
            // If the user does not exist, create a new user
            $user = new \App\Models\User();
            $user->name = $validated['contact_name'];
            $user->email = $validated['contact_email'];
            $user->phone = $validated['contact_phone'];
            // Set a default password or generate one
            $user->password = bcrypt('defaultpassword'); // Use a secure password generation method in production
            Log::info("Creating new user", $user->toArray());
            $user->save();
        }
        
        // Generate a unique job number
        $jobNumber = self::generateOrderNumber();
        
        // Log the job request creation
        Log::info('Job request creation initiated', [
            'user_id' => $user->id,
            'job_number' => $jobNumber,
            'data' => $validated
        ]);
        // Create the job request with the validated data
        $jobRequest = new \App\Models\JobRequest();
        $jobRequest->fill($validated);
        $jobRequest->job_number = $jobNumber;
        $jobRequest->user_id = $user->id; // Either the found user or the newly created user
        $jobRequest->status = 'Pending';
        $jobRequest->save();
        Log::info('Job request created', ['job_request' => $jobRequest]);
        // Send a confirmation email to the user
        // \Mail::to($validated['contact_email'])->send(new \App\Mail\JobRequestConfirmation($jobRequest));
        
        // Redirect to a confirmation page or back to dashboard
        return redirect()->route('job-requests.index')
            ->with('success', 'Your job request has been submitted successfully! Your job number is: ' . $jobNumber);
    
    }

    public function show($id)
    {
        // Fetch the user and check user type
        $loggedInUser = auth()->user();
        // Logic to show a specific job request
        $jobRequest = \App\Models\JobRequest::findOrFail($id);
        // Check if the job request belongs to the logged-in user
        if ($jobRequest->user_id !== $loggedInUser->id) {
            // If the job request does not belong to the logged-in user
            return redirect()->route('job-requests.index')->with('error', 'You do not have permission to view this job request.');
        }
        // If it does belong, show the job request details
        return view('job_requests.show', compact('jobRequest'));
    }

    public function edit($id)
    {
        // Logic to show the form for editing a specific job request
    }

    public function update(Request $request, $id)
    {
        // Logic to update a specific job request
    }

    public function destroy($id)
    {
        // Logic to delete a specific job request
    }



    // Helper Methods
    /**
     * Generate a unique job request number.
     *
     * @return string
     */
    // This method generates a unique job request number using the current timestamp and a random number
    private static function generateOrderNumber()
    {
        // Use the current timestamp and a random number to ensure uniqueness
        $timestamp = now()->format('YmdHis'); // Format: YYYYMMDDHHMMSS
        $randomNumber = mt_rand(1000, 9999); // Generate a random 4-digit number
    
        return 'JOB-' . $timestamp . '-' . $randomNumber; // Example: ORD-20250407123045-1234
    }
}
