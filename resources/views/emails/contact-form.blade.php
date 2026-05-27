<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Contact Form Submission</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.5; color: #1f2937;">
    <h2 style="margin: 0 0 16px;">New Contact Form Submission</h2>
    <p style="margin: 0 0 8px;"><strong>Name:</strong> {{ $name !== '' ? $name : 'N/A' }}</p>
    <p style="margin: 0 0 8px;"><strong>Email:</strong> {{ $email }}</p>
    <p style="margin: 0 0 8px;"><strong>Message:</strong></p>
    <p style="margin: 0; white-space: pre-line;">{{ $userMessage !== '' ? $userMessage : 'N/A' }}</p>
</body>
</html>
