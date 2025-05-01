<!DOCTYPE html>
<html>
<head>
    <title>Job Request Confirmation</title>
</head>
<body>
    <h1>Thank you for your job request!</h1>
    <p>Dear {{ $jobRequest->contact_name }},</p>
    <p>Your job request has been successfully submitted. Here are the details:</p>
    <ul>
        <li><strong>Job Number:</strong> {{ $jobRequest->job_number }}</li>
        <li><strong>Job Type:</strong> {{ $jobRequest->job_type }}</li>
        <li><strong>Urgency Level:</strong> {{ $jobRequest->urgency_level }}</li>
        <li><strong>Description:</strong> {{ $jobRequest->job_description }}</li>
    </ul>
    <p>We will contact you shortly with further updates.</p>
    <p>Thank you,</p>
    <p>The RumahFix Team</p>
</body>
</html>