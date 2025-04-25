<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRequestImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobRequestAttachmentController extends Controller
{
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'job_request_id' => 'required|exists:job_requests,id',
            'image_type' => 'nullable|in:user_upload,admin_upload,internal,document,billing,image',
            'caption' => 'nullable|string|max:255',
            'attachments' => 'nullable|array',
            'attachments.*' => 'file|mimes:jpg,jpeg,png,pdf,webp|max:2048',
        ]);

        $this->handleAttachments(
            $request->file('attachments'), 
            $request->input('job_request_id'), 
            auth()->user(), 
            $request->input('image_type'), 
            $request->input('caption')
        );

        return redirect()->route('admin.job-requests.show', $request->input('job_request_id'))->with('success', 'Attachment added successfully.');
    }
    public function update(Request $request, $id)
    {
        // Validate the request
        $request->validate([
            'image_type' => 'nullable|in:user_upload,admin_upload,internal,document,billing,image',
            'caption' => 'nullable|string|max:255',
            'is_visible_to_customer' => 'nullable|boolean',
            'is_active' => 'nullable|boolean',
        ]);

        // Find the attachment by ID
        $attachment = JobRequestImage::findOrFail($id);
        // Check the job request to ensure the user has permission to update it
        $jobRequest = $attachment->jobRequest;
        self::blockIfNotAdminOrAssignedWorker($jobRequest);

        // Update the attachment details
        $attachment->image_type = $request->input('image_type', $attachment->image_type);
        $attachment->caption = $request->input('caption', $attachment->caption);
        $attachment->is_visible_to_customer = $request->input('is_visible_to_customer', $attachment->is_visible_to_customer);
        $attachment->is_active = $request->input('is_active', $attachment->is_active);
        // Save the changes
        $attachment->save();
        
        return redirect()->route('admin.job-requests.show', $jobRequest->id)->with('success', 'Attachment updated successfully.');
    }

    public function destroy($id)
    {
        // Find the attachment by ID
        $attachment = JobRequestImage::findOrFail($id);

        // Check the job request to ensure the user has permission to delete it
        $jobRequest = $attachment->jobRequest;

        // Check if the authenticated user is the associated worker or an admin
        self::blockIfNotAdminOrAssignedWorker($jobRequest);

        // Delete the attachment
        $attachment->delete();

        // Redirect to job request show page
        return redirect()->route('admin.job-requests.show', $jobRequest->id)->with('success', 'Attachment deleted successfully.');
    }


    // HELPER METHODS
    /**
     * Block the action if the user is not an admin or the assigned worker for the job request.
     *
     * @param \App\Models\JobRequest $jobRequest
     * @return void
     */
    private function blockIfNotAdminOrAssignedWorker($jobRequest)
    {
        // Check if the authenticated user is the associated worker or an admin
        if (auth()->user()->user_type !== 'admin') {
            if ($jobRequest->worker_id !== auth()->id()) {
                //                // If not, block the action
                abort(403, 'You do not have permission to perform this action.');
            }
        }
    }
    // This method handles the S3 upload of an image
    private function handleS3Upload($file, $jobRequestId, $attachmentType)
    {
        // Handle S3 upload logic here
        $path = $file->store('job-requests/' . $jobRequestId . "/" . $attachmentType, 's3');
        return $path;
    }
    // This method takes the validated request, the job request object, and user
    private function handleAttachments($requestImages, $jobRequestId, $user, $attachmentType, $imageCaption)
    {
        // Check if the request has images
        foreach ($requestImages as $image) {
            // Handle S3 upload logic here
            $path = $this->handleS3Upload($image, $jobRequestId, $attachmentType);
            
            // Create a new JobRequestImage instance
            $jobRequestImage = new \App\Models\JobRequestImage();
    
            $jobRequestImage->fill([
                'job_request_id' => $jobRequestId,
                'user_id' => $user->id,
                'path' => $path,
                'caption' => $imageCaption,
                'original_filename' => $image->getClientOriginalName(),
                'file_type' => $image->getClientMimeType(),
                'file_size' => $image->getSize(),
                'image_type' => $attachmentType,
                'is_visible_to_customer' => true,
                'is_active' => true,
            ]);
            
            $jobRequestImage->save();
        }
    }
}
