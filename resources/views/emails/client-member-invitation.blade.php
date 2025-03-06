<!DOCTYPE html>
<html>
<head>
    <title>Invitation</title>
</head>
<body>
    <h1>Hello {{ $user->name }},</h1>
    <p>You have been invited to join our platform.</p>
    <div>
        <ul>
            <li>Email: {{ $user->email }}</li>
            <li>Password: {{ $password }}</li>
        </ul>
    </div>
    <a href="{{ url("/client-member-invitation/$password") }}" style="display: inline-block; padding: 10px 20px; background-color: blue; color: white; text-decoration: none;">
        Accept Invitation
    </a>
    <p>Thank you!</p>
</body>
</html>
