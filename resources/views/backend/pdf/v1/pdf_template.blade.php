<!DOCTYPE html>
<html>
<head>
    <title>PDF Document</title>
</head>
<body>
    <h1>{{ $name }}</h1>
    <p>Type: {{ $type }}</p>
    <img src="{{ $image }}" alt="Image">
    <p>Website: <a href="{{ $website }}">{{ $website }}</a></p>
    <p>Score: {{ $score }}</p>
</body>
</html>
