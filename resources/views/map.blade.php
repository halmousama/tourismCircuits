<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenStreetMap Embed</title>
    <!-- Link to home.css -->
    <link rel="stylesheet" href="{{ asset('css/map.css') }}" />
</head>
<body>
    <!-- Map Container -->
     <div class="map-container">
    <div id="topbar">
    <h3><a href="/home" style="padding: 10px; text-decoration: none; font-family: monospace, Arial, Helvetica, sans-serif;"> <=LOGO</a></h3>        <form action="{{ route('store.circuit') }}" method="post">
            @csrf
            <input type="text" name="name" id="name" required>
            <input type="text" name="username" id="username" hidden>
            <input type="text" name="coord" id="coord" hidden>
            <input type="submit" value="Add" id="add-coord">
        </form>
        <div id="ciruit-map">
            <table id="circuit-history">
            </table>
        </div>
    </div>
    <div id="map"></div>
    </div>
    <!-- Link to home.js -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="{{ asset('js/map.js') }}"></script>
</body>
</html>