<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Email Verification</title>
</head>
<body>
    <h1>Hello {{$user->name }}</h1>
    <h3>This is your verification code</h3>
    <p>{{ $user->verification_code }}</p>
</body>
</html>