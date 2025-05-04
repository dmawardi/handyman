<!DOCTYPE html>
<html>
<head>
    <title>Job Request Status Update</title>
</head>
<body>
    <h1>Job Request Status Update</h1>
    <p>Dear {{ $jobRequest->contact_name }},</p>
    <p>We wanted to inform you that the status of your job request has been updated. Here are the details:</p>
    <ul>
        <li><strong>Job Number:</strong> {{ $jobRequest->job_number }}</li>
        <li><strong>Job Type:</strong> {{ $jobRequest->job_type }}</li>
        <li><strong>Current Status:</strong> {{ $jobRequest->status }}</li>
        <li><strong>Description:</strong> {{ $jobRequest->job_description }}</li>
        @if($jobRequest->completion_date)
            <li><strong>Completion Date:</strong> {{ $jobRequest->completion_date->format('F j, Y') }}</li>
        @endif
    </ul>
    <p>If you have any questions or need further assistance, please feel free to contact us.</p>
    <p>Thank you for choosing RumahFix!</p>
    <p>Best regards,</p>
    <p>The RumahFix Team</p>
</body>
</html>