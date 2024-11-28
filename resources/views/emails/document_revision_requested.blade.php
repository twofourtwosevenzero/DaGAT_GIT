<!DOCTYPE html>
<html>
<head>
    <title>Revision Requested</title>
</head>
<body>
    <p>Dear {{ $document->user->name }},</p>

    <p>The document "<strong>{{ $document->Description }}</strong>" has received a revision request.</p>

    <p><strong>Requested by:</strong> {{ $office->Office_Name }}</p>
    <p><strong>Revision Type:</strong> {{ $revisionType }}</p>
    <p><strong>Reason:</strong> {{ $revisionReason }}</p>

    <p>Please review and make the necessary changes.</p>

    <p>Thank you.</p>
</body>
</html>
