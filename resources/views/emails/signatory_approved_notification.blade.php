<!DOCTYPE html>
<html>
<head>
    <title>Document Approved</title>
</head>
<body>
    <p>Dear {{ $document->user->name }},</p>

    <p>The document "<strong>{{ $document->Description }}</strong>" has been approved by {{ $signatory->office->Office_Name }}.</p>

    <p>Thank you.</p>
</body>
</html>
