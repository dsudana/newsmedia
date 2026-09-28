<!DOCTYPE html>
<html>
<head>
    <title>Asset Debug</title>
</head>
<body>
    <h1>Asset Helper Debug</h1>
    <p>asset('storage'): {{ asset('storage') }}</p>
    <p>asset('storage/test.jpg'): {{ asset('storage/test.jpg') }}</p>
    <p>Concatenated: {{ asset('storage') }}/articles/2024/09/test.jpeg</p>
</body>
</html>
