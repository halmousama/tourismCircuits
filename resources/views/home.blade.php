<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/home.css') }}">
    <title>HOME</title>
</head>
<body>
    <div class="container">
        <div class="topbar">
            <div class="left"><h3>HOME</h3></div>
            <div class="middle"></div>
            <div class="right"></div>
        </div>
        <div class="content">
            
        </div>
    </div>

    <script src="{{ asset('js/home.js') }}"></script>
</body>
</html>