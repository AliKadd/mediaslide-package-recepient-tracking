<!DOCTYPE html>
<html>
<head>
    <title>{{ $modelProfile->name }} - Profile</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { font-size: 20px; }
        p { margin: 4px 0; }
    </style>
</head>
<body>
    <h1>{{ $modelProfile->name }}</h1>
    <img src="{{ $modelProfile->image }}" style="max-width:200px;">
    <p><strong>About:</strong> {{ $modelProfile->about }}</p>
    <p><strong>Metadata:</strong></p>
    <pre>{{ json_encode($modelProfile->metadata, JSON_PRETTY_PRINT) }}</pre>
</body>
</html>
