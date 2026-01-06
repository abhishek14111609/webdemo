<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Password Reset OTP</title>
</head>
<body style="font-family: Arial, sans-serif; color:#333;">
    <h2 style="color:#222;">Password Reset OTP</h2>
    <p>Hello,</p>
    <p>Use the following One-Time Password (OTP) to reset your password:</p>
    <p style="font-size:24px; font-weight:bold; letter-spacing:3px;">{{ $otp }}</p>
    <p>This OTP will expire in {{ $expiresMinutes }} minutes. If you did not request a password reset, please ignore this email.</p>
    <p>Thank you,<br>{{ config('app.name') }}</p>
</body>
</html>
