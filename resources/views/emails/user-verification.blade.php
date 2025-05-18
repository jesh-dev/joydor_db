<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Email Verification</title>
</head>
<body>
    <div class='bg-orange-500 '>
        <h1 class=' bg-gray-500 ml-20 align-center rounded-xl flex flex-col w-100'>Hello {{ $user->firstname . $user->lastname }}</h1>
        <h3 class='px-5 text-md '>This is your verification code:</h3>
        <p class='px-5 font-bold text-2xl'>{{ $user->verification_code }}</p>
    </div>
    </body>
</html>
