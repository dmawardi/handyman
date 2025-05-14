{{-- filepath: /Users/d/Web Development/projects/handyman/resources/views/emails/welcome_new_user_created.blade.php --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to RumahFix</title>
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
        .btn {
            display: inline-block;
            padding: 10px 20px;
            background: #4CAF50;
            color: #ffffff;
            text-decoration: none;
            border-radius: 5px;
            margin-top: 20px;
        }
        .btn:hover {
            background: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <h1>Welcome to RumahFix!</h1>
        </div>

        <!-- Body -->
        <div class="email-body">
            <p>Hi {{ $jobRequest->contact_name }},</p>
            <p>We’re excited to welcome you to RumahFix! Your account has been successfully created, and you can now access our platform to manage your job requests and services.</p>
            
            <p><strong>Your login details:</strong></p>
            <ul>
                <li><strong>Email:</strong> {{ $jobRequest->contact_email }}</li>
                <li><strong>Password:</strong> {{ $generatedPassword }}</li>
            </ul>
            
            <p>For security reasons, we recommend changing your password after logging in for the first time.</p>
            
            <a href="{{ route('login') }}" class="btn">Log In to Your Account</a>
        </div>

        <!-- Footer -->
        <div class="email-footer">
            <p>If you have any questions, feel free to contact us at support@rumahfix.com.</p>
            <p>&copy; {{ date('Y') }} RumahFix. All rights reserved.</p>
        </div>
    </div>
</body>
</html>