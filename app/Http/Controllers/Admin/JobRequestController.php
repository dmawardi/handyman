<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRequest;
use App\Models\JobUpdate;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
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

            // Generate a random password
            $genPassword = \Illuminate\Support\Str::random(16);

            // Create a new user
            $user = User::create([
                'name' => $request->contact_name,
                'email' => $request->contact_email,
                'phone' => $request->contact_phone,
                'user_type' => 'customer',
                'password' => bcrypt($genPassword), // Generate random password
            ]);

            // Send email to the new user with the generated password
            Mail::to($user->email)->queue(new \App\Mail\WelcomeNewUser($user, $genPassword));

            // Add new user details to the request
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
            // attachments
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf|max:5048',
        ])->validate();
        
        // Create the job request
        $jobRequest = new JobRequest();
        $jobRequest->user_id = $validated['user_id'];
        $jobRequest->contact_name = $validated['contact_name'];
        $jobRequest->contact_email = $validated['contact_email'];
        $jobRequest->contact_phone = $validated['contact_phone'];
        $jobRequest->worker_id = $validated['worker_id'] ?? null;
        $jobRequest->api_search = $validated['api_search'] ?? null;
        $jobRequest->street_address = $validated['street_address'] ?? null;
        $jobRequest->city = $validated['city'] ?? null;
        $jobRequest->state = $validated['state'] ?? null;
        $jobRequest->suburb = $validated['suburb'] ?? null;
        $jobRequest->area = $validated['area'] ?? null;
        $jobRequest->zip_code = $validated['zip_code'] ?? null;
        $jobRequest->location = $validated['location'] ?? null;
        $jobRequest->latitude = $validated['latitude'] ?? null;
        $jobRequest->longitude = $validated['longitude'] ?? null;
        $jobRequest->job_type = $validated['job_type'];
        $jobRequest->urgency_level = $validated['urgency_level'];
        $jobRequest->job_budget = $validated['job_budget'] ?? null;
        $jobRequest->job_description = $validated['job_description'];
        $jobRequest->status = $validated['status'];
        $jobRequest->notes = $validated['notes'] ?? null;
        
        // Set completion date if status is Completed
        if ($validated['status'] === 'Completed') {
            $jobRequest->completion_date = now();
        }
        
        $jobRequest->save();

        // Handle attachment uploads
        if ($request->hasFile('attachments')) {
            // Loop through each image, upload it to S3, and save the path
            $this->handleAttachments($validated['attachments'], $jobRequest, $user);
        }
        
        return redirect()->route('admin.job-requests.index')
            ->with('success', 'Job request created successfully. Job number: ' . $jobRequest->job_number);
    }

    /**
     * Display the specified job request.
     */
    public function show(JobRequest $jobRequest)
    {
        $jobRequest = cache()->remember('admin_job_request_' . $jobRequest->id, 60, function () use ($jobRequest) {
            return JobRequest::with(['requestor', 'worker', 'noteUpdates' => function ($query) {
                $query->orderBy('created_at', 'asc');
            }, 'attachments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])->find($jobRequest->id);
        });
        // Load associated user and worker
        $jobRequest->load(['requestor', 'worker', 'noteUpdates' => function ($query) {
            $query->orderBy('created_at', 'asc');
        }, 'attachments' => function ($query) {
            $query->orderBy('created_at', 'desc');
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

        $jobRequest = cache()->remember('admin_job_request_' . $jobRequest->id, 60, function () use ($jobRequest) {
            return JobRequest::with(['requestor', 'worker', 'attachments' => function ($query) {
                $query->orderBy('created_at', 'desc');
            }])->find($jobRequest->id);
        });

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
        // Preprocess the request to remove empty values from delete_attachments
        // This is to ensure that we only send non-empty values to the database
        $request->merge([
            'delete_attachments' => array_filter($request->input('delete_attachments', [])),
        ]);

        Log::info('Request: ', $request->all());

        // Validate the request data
        $validated = $request->validate([
            'worker_id' => 'nullable|exists:users,id',
            'contact_name' => 'nullable|string|max:255',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
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
            'job_type' => 'nullable|string|in:Plumbing,Electrical,Painting,Appliance Repair,Outdoor/Garden,Installations,Cleaning/Maintenance,Other',
            'urgency_level' => 'nullable|string|in:Low - Within 2 weeks,Medium - Within 1 week,High - Within 48 hours,Emergency - Same day',
            'job_budget' => 'nullable|numeric|min:0',
            'job_description' => 'nullable|string',
            'status' => 'nullable|string|in:Pending,In Progress,Completed,Cancelled',
            'notes' => 'nullable|string',
            // attachments
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,webp|max:5048',
            'delete_attachments' => 'nullable|array',
            'delete_attachments.*' => 'nullable|exists:job_request_attachments,id',
            // Financial information
            'payment_method' => 'nullable|string|max:255',
            'payment_status' => 'nullable|string|in:Pending,Completed,Failed',
            'transaction_id' => 'nullable|string|max:255',
            'invoice_number' => 'nullable|string|max:255',
            'payment_amount' => 'nullable|numeric|min:0',
            'payment_date' => 'nullable|date',
            'payment_receipt' => 'nullable|string|max:255',
            'full_amount' => 'nullable|numeric|min:0',
        ]);
        
        // Update completion date based on status change
        $oldStatus = $jobRequest->status;
        $newStatus = $validated['status'] ?? null;

        if ($oldStatus !== 'Completed' && $newStatus === 'Completed') {
            // Job was just marked complete
            $jobRequest->completion_date = now();

            // Send an email to the requestor
            Mail::to($jobRequest->requestor->email)->queue(new \App\Mail\JobRequestStatusUpdate($jobRequest));
        } elseif ($oldStatus === 'Completed' && $newStatus !== 'Completed') {
            // Job was un-completed
            $jobRequest->completion_date = null;
            
            // Send an email to the requestor
            Mail::to($jobRequest->requestor->email)->queue(new \App\Mail\JobRequestStatusUpdate($jobRequest));
        }
        
        // Strip out the attachments and delete_attachments
        $validated = $request->except(['attachments', 'delete_attachments', 'job_request_id']);

        // Update the job request
        $jobRequest->update($validated);
        // Grab logged in user
        $user = auth()->user();

        // Handle attachment uploads
        if ($request->hasFile('attachments')) {
            // Loop through each image, upload it to S3, and save the path
            $this->handleAttachments($request->attachments, $jobRequest, $user);
        }

        // Delete the attachment from S3
        if ($request->has('delete_attachments')) {
            // Loop through each attachment, and delete from S3
            $this->handleAtachmentDeletions($request->delete_attachments);
        }

        // Generate a cache key based on the job request ID
        $cacheKey = 'admin_job_request_' . $jobRequest->id;
        // CLear cache
        cache()->forget($cacheKey);

        // Re-fetch the job request with attachments and set as cache key for 10 minutes
        cache()->remember($cacheKey, now()->addMinutes(10), function () use ($jobRequest) {
            return \App\Models\JobRequest::with(['requestor', 'worker', 'attachments'])->findOrFail($jobRequest->id);
        });
        
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

        // Clear cache
        $cacheKey = 'admin_job_request_' . $jobRequest->id;
        cache()->forget($cacheKey);
        
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

        // Clear cache
        $cacheKey = 'admin_job_request_' . $jobRequest->id;
        cache()->forget($cacheKey);
                
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

        // Send an email to the requestor with news of the update
        Mail::to($jobRequest->requestor->email)->queue(new \App\Mail\JobRequestStatusUpdate($jobRequest));
        
        $jobRequest->save();

        // Clear cache
        $cacheKey = 'admin_job_request_' . $jobRequest->id;
        cache()->forget($cacheKey);
        
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

        // Clear cache
        $cacheKey = 'admin_job_request_' . $jobRequest->id;
        cache()->forget($cacheKey);
        
        return redirect()->route('admin.job-requests.show', $jobRequest)
            ->with('success', 'Notes added successfully.');
    }
    
    // Helper methods
    // This method handles the S3 upload of an image
    private function handleS3Upload($file, $jobRequestId)
    {
        // Handle S3 upload logic here
        $path = $file->store('job-requests/' . $jobRequestId . "/user-uploads" , 's3');
        return $path;
    }
    // This method takes the validated request, the job request object, and user
    // and handles the image attachments
    private function handleAttachments($requestAttachments, $jobRequest, $user)
    {
        // Check if the request has attachments
        if ($requestAttachments) {
            // Loop through each image, upload it to S3, and save the path
            foreach ($requestAttachments as $image) {
                // Handle S3 upload logic here
                $path = $this->handleS3Upload($image, $jobRequest->id);
                
                // Create a new JobRequestAttachment instance
                $jobRequestAttachment = new \App\Models\JobRequestAttachment();
        
                $jobRequestAttachment->fill([
                    'job_request_id' => $jobRequest->id,
                    'user_id' => $user->id,
                    'path' => $path,
                    'original_filename' => $image->getClientOriginalName(),
                    'file_type' => $image->getClientMimeType(),
                    'file_size' => $image->getSize(),
                    'type' => 'admin_upload',
                    'is_visible_to_customer' => true,
                    'is_active' => true,
                ]);
                
                $jobRequestAttachment->save();
            }
        }
    }
    // Takes a list of IDs and an image ID
    private function handleAtachmentDeletions($listOfIds)
    {
        foreach($listOfIds as $id) {
            // Find the attachment by ID
            $attachment = \App\Models\JobRequestAttachment::findOrFail($id);
            
            // Delete the attachment from S3
            if ($attachment->path) {
                Storage::disk('s3')->delete($attachment->path);
            }
            
            // Delete the attachment record from the database
            $attachment->delete();
        }
    }
}
