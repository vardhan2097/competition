<!DOCTYPE html>
<html>
<head>
    <title>You're Invited</title>
</head>
<body>
    <p>Hello,</p>

    <p>You’ve been invited to join <strong>{{ $orgName }}</strong> as <strong>{{ $designation }}</strong>.</p>

    <p>Please click the link below to accept the invitation and complete your registration:</p>

    <p><a href="{{ $inviteUrl }}">{{ $inviteUrl }}</a></p>

    <p>This link will expire in a few days. If you weren’t expecting this, you can ignore this email.</p>

    <p>Thanks,<br>Team {{ $orgName }}</p>
</body>
</html>
