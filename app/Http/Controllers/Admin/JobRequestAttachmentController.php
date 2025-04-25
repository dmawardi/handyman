<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\JobRequestImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class JobRequestAttachmentController extends Controller
{
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
}
