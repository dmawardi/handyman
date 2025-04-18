<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use Illuminate\Http\Request;

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
        // Validate the request data
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id',
            'worker_id' => 'nullable|exists:users,id',
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
            'status' => 'required|string|in:Pending,In Progress,Completed,Cancelled',
            'notes' => 'nullable|string',
        ]);
        
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
        
        Log::info('Admin created job request', [
            'admin_id' => auth()->id(),
            'job_request' => $jobRequest->toArray()
        ]);
        
        return redirect()->route('admin.job_requests.index')
            ->with('success', 'Job request created successfully. Job number: ' . $jobNumber);
    }

    /**
     * Display the specified job request.
     */
    public function show(JobRequest $jobRequest)
    {
        // Load associated user and worker
        $jobRequest->load(['user', 'worker']);
        
        return view('admin.job_requests.show', compact('jobRequest'));
    }

    /**
     * Show the form for editing the specified job request.
     */
    public function edit(JobRequest $jobRequest)
    {
        $users = User::where('user_type', 'customer')->get();
        $workers = User::where('user_type', 'worker')->get();
        return view('admin.job_requests.edit', compact('jobRequest', 'users', 'workers'));
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
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
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
        
        Log::info('Admin updated job request', [
            'admin_id' => auth()->id(),
            'job_request_id' => $jobRequest->id,
            'changes' => $validated
        ]);
        
        return redirect()->route('admin.job_requests.show', $jobRequest)
            ->with('success', 'Job request updated successfully.');
    }

    /**
     * Remove the specified job request from storage.
     */
    public function destroy(JobRequest $jobRequest)
    {
        // Log the deletion
        Log::info('Admin deleted job request', [
            'admin_id' => auth()->id(),
            'job_request' => $jobRequest->toArray()
        ]);
        
        // Permanently delete the job request
        $jobRequest->delete();
        
        return redirect()->route('admin.job_requests.index')
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
        
        Log::info('Admin assigned worker to job request', [
            'admin_id' => auth()->id(),
            'job_request_id' => $jobRequest->id,
            'worker_id' => $validated['worker_id'],
            'worker_name' => $worker->name
        ]);
        
        return redirect()->route('admin.job_requests.show', $jobRequest)
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
        
        Log::info('Admin updated job request status', [
            'admin_id' => auth()->id(),
            'job_request_id' => $jobRequest->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus
        ]);
        
        return redirect()->route('admin.job-requests.show', $jobRequest)
            ->with('success', 'Job request status updated successfully.');
    }
    
    /**
     * Add notes to a job request.
     */
    public function addNotes(Request $request, JobRequest $jobRequest)
    {
        $validated = $request->validate([
            'notes' => 'required|string',
        ]);
        
        $jobRequest->notes = $validated['notes'];
        $jobRequest->save();
        
        Log::info('Admin added notes to job request', [
            'admin_id' => auth()->id(),
            'job_request_id' => $jobRequest->id,
            'notes' => $validated['notes']
        ]);
        
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
