<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

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
            // API search result
            'api_search' => 'nullable|string|max:255',
            // Address information
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'suburb' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'job_type' => 'required|string|in:Plumbing,Electrical,Painting,Appliance Repair,Outdoor/Garden,Installations,Cleaning/Maintenance,Other',
            'urgency_level' => 'required|string|in:Low - Within 2 weeks,Medium - Within 1 week,High - Within 48 hours,Emergency - Same day',
            'job_budget' => 'nullable|numeric|min:0',
            'job_description' => 'required|string',
            'attachments' => 'nullable|array',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120', // Validate images
        ]);

        // Find the user by email
        $user = \App\Models\User::where('email', $validated['contact_email'])->first();
       
        if (!$user) {
            // If the user does not exist, create a new user
            $user = new \App\Models\User();
            $user->name = $validated['contact_name'];
            $user->email = $validated['contact_email'];
            $user->phone = $validated['contact_phone'];
            // Set a default password or generate one
            $user->password = bcrypt('defaultpassword'); // Use a secure password generation method in production
            $user->save();
        }
        
        // Remove attachments from validated data for job request creation
        $dataForJobRequest = collect($validated)->except(['attachments'])->toArray();

        // Create the job request with the validated data
        $jobRequest = new \App\Models\JobRequest();
        $jobRequest->fill($dataForJobRequest);
        $jobRequest->user_id = $user->id; // Either the found user or the newly created user
        $jobRequest->status = 'Pending';
        $jobRequest->save();

        // Handle attachment uploads
        if ($request->hasFile('attachments')) {
            // Loop through each attachment, upload it to S3, and save the path
            $this->handleImageAttachments($validated['attachments'], $jobRequest, $user);
        }
        
        // Send a confirmation email to the user
        Mail::to($user->email)->queue(new \App\Mail\JobRequestCreationConfirmation($jobRequest));
        
        // Redirect to a confirmation page or back to dashboard
        return redirect()->route('job-requests.create')
            ->with('success', 'Your job request has been submitted successfully! Your job number is: ' . $jobRequest->job_number);
    
    }

    public function show($id)
    {
        // Fetch the user and check user type
        $loggedInUser = auth()->user();
        // Logic to show a specific job request
        $jobRequest = \App\Models\JobRequest::findOrFail($id)->load('attachments');
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
        $loggedInUser = auth()->user();
        $jobRequest = \App\Models\JobRequest::findOrFail($id);
        
        // Check if the job request belongs to the logged-in user
        if ($jobRequest->user_id !== $loggedInUser->id) {
            return redirect()->route('job-requests.index')
                ->with('error', 'You do not have permission to edit this job request.');
        }
        
        // Only allow editing of pending job requests
        if ($jobRequest->status !== 'Pending') {
            return redirect()->route('job-requests.show', $jobRequest->id)
                ->with('error', 'Only pending job requests can be edited.');
        }
        
        return view('job_requests.edit', ['jobRequest' => $jobRequest, 'apiKey' => env('GOOGLE_API_KEY')]);
    }

    public function update(Request $request, $id)
    {
        // Preprocess the request to remove empty values from delete_attachments
        // This is to ensure that we only send non-empty values to the database
        $request->merge([
            'delete_attachments' => array_filter($request->input('delete_attachments', [])),
        ]);
        
        // Fetch the user and the job request
        $loggedInUser = auth()->user();
        $jobRequest = \App\Models\JobRequest::findOrFail($id);
        
        // Check if the job request belongs to the logged-in user
        if ($jobRequest->user_id !== $loggedInUser->id) {
            return redirect()->route('job-requests.index')
                ->with('error', 'You do not have permission to update this job request.');
        }
        
        // Only allow updating of pending job requests
        if ($jobRequest->status !== 'Pending') {
            return redirect()->route('job-requests.show', $jobRequest->id)
                ->with('error', 'Only pending job requests can be updated.');
        }
        
        // Validate the request data - customer facing fields only
        $validated = $request->validate([
            'contact_name' => 'required|string|max:255',
            'contact_phone' => 'required|string|max:20',
            // API search result
            'api_search' => 'nullable|string|max:255',
            // Address information
            'street_address' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'suburb' => 'nullable|string|max:100',
            'area' => 'nullable|string|max:100',
            'zip_code' => 'nullable|string|max:20',
            'location' => 'nullable|string|max:255',
            'latitude' => 'nullable|string|max:20',
            'longitude' => 'nullable|string|max:20',
            'job_type' => 'required|string|in:Plumbing,Electrical,Painting,Appliance Repair,Outdoor/Garden,Installations,Cleaning/Maintenance,Other',
            'urgency_level' => 'required|string|in:Low - Within 2 weeks,Medium - Within 1 week,High - Within 48 hours,Emergency - Same day',
            'job_budget' => 'nullable|numeric|min:0',
            'job_description' => 'required|string',
            'attachments.*' => 'nullable|file|mimes:jpeg,png,jpg,gif,webp|max:5120', // Validate attachments
            'delete_attachments' => 'nullable|array',
            'delete_attachments.*' => 'exists:job_request_attachments,id', // Validate image IDs for deletion
        ]);
        // Note: We don't allow changing the email as it's tied to user identity

        // Remove attachments from validated data for job request creation
        $dataForJobRequest = collect($validated)->except(['attachments', 'delete_attachments'])->toArray();

        // Update the job request with validated data
        $jobRequest->update($dataForJobRequest);

        // Handle attachment uploads
        if ($request->hasFile('attachments')) {
            // Loop through each image, upload it to S3, and save the path
            $this->handleImageAttachments($validated['attachments'], $jobRequest, $loggedInUser);
        }

        // Handle attachment deletions
        if ($request->has('delete_attachments')) {
            $this->handleImageAtachmentDeletions($validated['delete_attachments']);
        }
        
        
        return redirect()->route('job-requests.show', $jobRequest->id)
            ->with('success', 'Job request updated successfully!');
    }

    public function destroy($id)
    {
        // Fetch the user and job request
        $loggedInUser = auth()->user();
        $jobRequest = \App\Models\JobRequest::findOrFail($id);
        
        // Check if the job request belongs to the logged-in user
        if ($jobRequest->user_id !== $loggedInUser->id) {
            return redirect()->route('job-requests.index')
                ->with('error', 'You do not have permission to cancel this job request.');
        }
        
        // Only allow cancellation of pending job requests
        if ($jobRequest->status !== 'Pending') {
            return redirect()->route('job-requests.show', $jobRequest->id)
                ->with('error', 'Only pending job requests can be cancelled.');
        }
        
        // Change status to cancelled (soft delete)
        $jobRequest->status = 'Cancelled';
        $jobRequest->save();
                
        return redirect()->route('job-requests.index')
            ->with('success', 'Job request cancelled successfully.');
    }

    
    
    
    // Helper Methods
    // This method handles the S3 upload of an image
    private function handleS3Upload($file, $jobRequestId)
    {
        // Handle S3 upload logic here
        $path = $file->store('job-requests/' . $jobRequestId . "/user-uploads" , 's3');
        return $path;
    }
    // This method takes the validated request, the job request object, and user
    // and handles the image attachments
    private function handleImageAttachments($requestImages, $jobRequest, $user)
    {
        // Check if the request has images
        foreach ($requestImages as $image) {
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
                'type' => 'user_upload',
                'is_visible_to_customer' => true,
                'is_active' => true,
            ]);
            
            $jobRequestAttachment->save();
        }
    }
    // Takes a list of IDs and an image ID
    private function handleImageAtachmentDeletions($listOfIds)
    {
        foreach($listOfIds as $id) {
            // Find the image by ID
            $image = \App\Models\JobRequestAttachment::findOrFail($id);
            
            // Delete the image from S3
            if ($image->path) {
                Storage::disk('s3')->delete($image->path);
            }
            
            // Delete the image record from the database
            $image->delete();
        }
    }
}
