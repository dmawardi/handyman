<!DOCTYPE html>
<html>
<head>
    <title>New Job Request Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background: #ffffff;
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }
        .email-header {
            background: #4CAF50;
            color: #ffffff;
            text-align: center;
            padding: 20px;
        }
        .email-body {
            padding: 20px;
        }
        .email-footer {
            background: #f1f1f1;
            text-align: center;
            padding: 10px;
            font-size: 12px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>New Job Request Created</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Dear Admin,</p>
            <p>A new job request has been submitted. Here are the details:</p>
            <ul>
                <li><strong>Job Number:</strong> {{ $jobRequest->job_number }}</li>
                <li><strong>Job Type:</strong> {{ $jobRequest->job_type }}</li>
                <li><strong>Urgency Level:</strong> {{ $jobRequest->urgency_level }}</li>
                <li><strong>Description:</strong> {{ $jobRequest->job_description }}</li>
                <li><strong>Submitted By:</strong> {{ $jobRequest->contact_name }} ({{ $jobRequest->contact_email }})</li>
            </ul>
            <p>You can view more details in the admin dashboard.</p>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} RumahFix. All rights reserved.</p>
        </div>
    </div>
</body>
</html>