<!DOCTYPE html>
<html>
<head>
    <title>Invitation</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    <p>You have been invited to join our urlshortner platform.</p>
    <a href="{{ url("/invitation/$password") }}" style="display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none;">
        Accept Invitation
    </a>
    <p>Thank you!</p>
</body>
</html>
