<!DOCTYPE html>
<html>
<head>
    <title>{{ $model->name }} - Profile</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        h1 { font-size: 20px; }
        p { margin: 4px 0; }
    </style>
</head>
<body>
    <h1>{{ $model->name }}</h1>
    <img src="{{ $model->image }}" style="max-width:200px;">
    <p><strong>About:</strong> {{ $model->about }}</p>
    <p><strong>Metadata:</strong></p>
    <pre>{{ json_encode($model->metadata, JSON_PRETTY_PRINT) }}</pre>
</body>
</html>
