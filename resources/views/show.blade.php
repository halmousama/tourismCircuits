<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenStreetMap Embed</title>
    <!-- Link to home.css -->
    <link rel="stylesheet" href="{{ asset('css/show.css') }}" />
</head>
<body>
    <!-- Map Container -->
     <div class="map-container">
    <div id="topbar">
        <h3><a href="/home" style="padding: 10px; text-decoration: none; font-family: monospace, Arial, Helvetica, sans-serif;"> <=LOGO</a></h3>
        <form action="{{ route('update.circuit', ['id' => $circuit->id]) }}" method="post">
            @csrf
            <input type="text" name="id" id="id" hidden value="{{ $circuit->id }}">
            <input type="text" name="name" id="name" required value="{{ $circuit->name }}">
            <input type="text" name="username" id="username" hidden value="{{ $username }}">
            <input type="text" name="coord" id="coord" hidden value="{{ $coordinates }}">
            <input type="submit" value="Save" id="update-coord">
            @if ($errors->has('update'))
            <span style="color: red; font-size: 14px; text-align: center; ">{{ $errors->first('update') }}</span>
            @endif
            @if (session('success'))
            <span style="color: green; font-size: 14px; text-align: center; ">{{ session('success') }}</span>
            @endif
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
    <script src="{{ asset('js/show.js') }}"></script>
</body>
</html>