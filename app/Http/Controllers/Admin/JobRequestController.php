<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\JobUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JobRequestController extends Controller
{
    /**
     * Display a listing of job requests.
     */
    public function index(Request $request)
    {
        $query = JobRequest::with(['requestor', 'worker']);
        
        // Apply filters if set
        if ($request->has('status') && $request->status != 'all') {
            $query->where('status', $request->status);
        }
        
        if ($request->has('job_type') && $request->job_type != 'all') {
            $query->where('job_type', $request->job_type);
        }
        
        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('job_number', 'like', "%{$search}%")
                  ->orWhere('contact_name', 'like', "%{$search}%")
                  ->orWhere('contact_email', 'like', "%{$search}%")
                  ->orWhere('contact_phone', 'like', "%{$search}%");
            });
        }
        
        // Sort by created_at descending by default
        $jobRequests = $query->orderBy('created_at', 'desc')->paginate(15);
        
        // Get all unique job types and statuses for filters
        $jobTypes = JobRequest::distinct()->pluck('job_type');
        $statuses = JobRequest::distinct()->pluck('status');
        
        return view('admin.job_requests.index', compact('jobRequests', 'jobTypes', 'statuses'));
    }

    /**
     * Show the form for creating a new job request.
     */
    public function create()
    {
        // Get all users and workers for the dropdowns
        $users = User::where('user_type', 'customer')->get();
        $workers = User::where('user_type', 'worker')->get();
        return view('admin.job_requests.create', compact('users', 'workers'));
    }

    /**
     * Store a newly created job request.
     */
    public function store(Request $request)
    {
        // Check if the job request is made for an existing user or a new one
        if ($request->customer_type === 'existing') {            
            // Validate for existing user
            $request->validate([
                'user_id' => 'required|exists:users,id',
            ]);

            // Find user and add contact details
            $user = User::find($request->user_id);
            $request->merge([
                'contact_name' => $user->name,
                'contact_email' => $user->email,
                'contact_phone' => $user->phone,
            ]);
        } else {
            // Else, validate for new user
            $request->validate([
                'contact_name' => 'required|string|max:255',
                'contact_email' => 'required|email|max:255|unique:users,email',
                'contact_phone' => 'required|string|max:20',
            ]);
            // Create a new user
            $user = User::create([
                'name' => $request->contact_name,
                'email' => $request->contact_email,
                'phone' => $request->contact_phone,
                'user_type' => 'customer',
                'password' => bcrypt('defaultpassword'), // Generate random password
            ]);
            $request->merge([
                'user_id' => $user->id,
                'contact_name' => $user->name,
                'contact_email' => $user->email,
                'contact_phone' => $user->phone,
            ]);
        }

        
        // Properly extract only the fields we need, excluding customer_type
        $validationData = $request->except(['customer_type']);
        
        // Validate the filtered data
        $validated = Validator::make($validationData, [
            'user_id' => 'required|exists:users,id',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'worker_id' => 'nullable|exists:users,id',
            // Api search result
            'api_search' => 'nullable|string|max:255',
            // Address information
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'suburb' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'job_type' => 'required|string|in:Plumbing,Electrical,Painting,Appliance Repair,Outdoor/Garden,Installations,Cleaning/Maintenance,Other',
            'urgency_level' => 'required|string|in:Low - Within 2 weeks,Medium - Within 1 week,High - Within 48 hours,Emergency - Same day',
            'job_budget' => 'nullable|numeric|min:0',
            'job_description' => 'required|string',
            'status' => 'required|string|in:Pending,In Progress,Completed,Cancelled',
            'notes' => 'nullable|string',
        ])->validate();

        // Generate a unique job number
        $jobNumber = $this->generateJobNumber();
        
        // Create the job request
        $jobRequest = new JobRequest();
        $jobRequest->fill($validated);
        $jobRequest->job_number = $jobNumber;
        
        // Set completion date if status is Completed
        if ($validated['status'] === 'Completed') {
            $jobRequest->completion_date = now();
        }
        
        $jobRequest->save();
        
        return redirect()->route('admin.job-requests.index')
            ->with('success', 'Job request created successfully. Job number: ' . $jobNumber);
    }

    /**
     * Display the specified job request.
     */
    public function show(JobRequest $jobRequest)
    {
        // Load associated user and worker
        $jobRequest->load(['requestor', 'worker', 'noteUpdates' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }]);
        $workers = User::where('user_type', 'worker')->get();
        
        return view('admin.job_requests.show', compact('jobRequest', 'workers'));
    }

    /**
     * Show the form for editing the specified job request.
     */
    public function edit(JobRequest $jobRequest)
    {
        $users = User::where('user_type', 'customer')->get();
        $workers = User::where('user_type', 'worker')->get();

        return view('admin.job_requests.edit', [
            'jobRequest' => $jobRequest, 
            'apiKey' => env('GOOGLE_API_KEY'),
            'users' => $users,
            'workers' => $workers,
            ]);
    }

    /**
     * Update the specified job request.
     */
    public function update(Request $request, JobRequest $jobRequest)
    {
        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'worker_id' => 'nullable|exists:users,id',
            'contact_name' => 'required|string|max:255',
            'contact_email' => 'required|email|max:255',
            'contact_phone' => 'required|string|max:20',
            // Api search result
            'api_search' => 'nullable|string|max:255',
            // Address information
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'suburb' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:255',
            'longitude' => 'nullable|string|max:255',
            'job_type' => 'required|string|in:Plumbing,Electrical,Painting,Appliance Repair,Outdoor/Garden,Installations,Cleaning/Maintenance,Other',
            'urgency_level' => 'required|string|in:Low - Within 2 weeks,Medium - Within 1 week,High - Within 48 hours,Emergency - Same day',
            'job_budget' => 'nullable|numeric|min:0',
            'job_description' => 'required|string',
            'status' => 'required|string|in:Pending,In Progress,Completed,Cancelled',
            'notes' => 'nullable|string',
        ]);
        
        // Update completion date based on status change
        $oldStatus = $jobRequest->status;
        $newStatus = $validated['status'];
        
        if ($oldStatus !== 'Completed' && $newStatus === 'Completed') {
            // Job was just marked complete
            $jobRequest->completion_date = now();
        } elseif ($oldStatus === 'Completed' && $newStatus !== 'Completed') {
            // Job was un-completed
            $jobRequest->completion_date = null;
        }
        
        // Update the job request
        $jobRequest->update($validated);
        
        return redirect()->route('admin.job-requests.show', $jobRequest)
            ->with('success', 'Job request updated successfully.');
    }

    /**
     * Remove the specified job request from storage.
     */
    public function destroy(JobRequest $jobRequest)
    {
        // Permanently delete the job request
        $jobRequest->delete();
        
        return redirect()->route('admin.job-requests.index')
            ->with('success', 'Job request deleted successfully.');
    }
    
    /**
     * Assign a worker to a job request.
     */
    public function assignWorker(Request $request, JobRequest $jobRequest)
    {
        $validated = $request->validate([
            'worker_id' => 'required|exists:users,id',
        ]);
        
        $jobRequest->worker_id = $validated['worker_id'];
        
        // If a worker is assigned and status is Pending, automatically change to In Progress
        if ($jobRequest->status === 'Pending') {
            $jobRequest->status = 'In Progress';
        }
        
        $jobRequest->save();
        
        $worker = User::find($validated['worker_id']);
        
        return redirect()->route('admin.job-requests.show', $jobRequest)
            ->with('success', 'Worker assigned successfully.');
    }
    
    /**
     * Update the status of a job request.
     */
    public function updateStatus(Request $request, JobRequest $jobRequest)
    {
        $validated = $request->validate([
            'status' => 'required|string|in:Pending,In Progress,Completed,Cancelled',
        ]);
        
        $oldStatus = $jobRequest->status;
        $newStatus = $validated['status'];
        
        $jobRequest->status = $newStatus;
        
        // Handle completion date
        if ($oldStatus !== 'Completed' && $newStatus === 'Completed') {
            $jobRequest->completion_date = now();
        } elseif ($oldStatus === 'Completed' && $newStatus !== 'Completed') {
            $jobRequest->completion_date = null;
        }
        
        $jobRequest->save();
        
        return redirect()->route('admin.job-requests.show', $jobRequest)
            ->with('success', 'Job request status updated successfully.');
    }
    
    /**
     * Add notes to a job request.
     */
    public function addNotes(Request $request, JobRequest $jobRequest)
    {
        $validated = $request->validate([
            'noteUpdate' => 'required|string',
        ]);
        // Add to relationship JobUpdate
        $jobUpdate = new JobUpdate();
        $jobUpdate->job_request_id = $jobRequest->id;
        $jobUpdate->update_type = 'note';
        $jobUpdate->update_description = $validated['noteUpdate'];
        $jobUpdate->user_id = auth()->id(); // Assuming the admin is the one adding the note
        $jobUpdate->save();
        
        return redirect()->route('admin.job-requests.show', $jobRequest)
            ->with('success', 'Notes added successfully.');
    }
    
    /**
     * Generate a unique job request number.
     */
    private function generateJobNumber()
    {
        $timestamp = now()->format('YmdHis');
        $randomNumber = mt_rand(1000, 9999);
        
        return 'JOB-' . $timestamp . '-' . $randomNumber;
    }
}
