<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OpenStreetMap Embed</title>
    <style>
        @import url('https://unpkg.com/leaflet/dist/leaflet.css');

        * {
            margin: 0;
            padding: 0;
        }

        .map-container {
            display: flex;
            justify-content: center;
            flex-direction: row;
            width: 100%;
            height: 100vh;
        }

        #map {
            height: 100vh;
            width: 100%;
        }

        .sidebar {
            width: 100%;
            height: 50px;
            background-color: #c01818;
            padding: 20px;
            z-index: 1000;
        }

        .delete-coord {
            background-color: transparent;
            color: red;
            border: none;
            cursor: pointer;
            font-size: 16px;
        }

        #circuit-history {
            padding: 10px;

            tr {
                text-align: center;
                background-color: thistle;
                border-radius: 10px;
                display: block;
                margin: 2px;

                td {
                    margin: 0;
                    padding: 0 10px;
                    border: none;
                }
            }
        }

        #update-coord {
            background-color: transparent;
            color: rgb(0, 0, 0);
            border: 1px solid tomato;
            border-radius: 10px;
            cursor: pointer;
            font-size: 1.1rem;
            transition: all 0.2s ease-in-out;
            display: block;
            margin: 5px auto;
            padding: 5px 10px;
        }

        form {
            padding: 10px;
        }

        form input[type="text"] {
            margin: 5px auto;
            padding: 5px 10px;
            border: 1px solid #ccc;
            border-radius: 10px;
            box-sizing: border-box;
            width: 100%;
        }

        #update-coord:hover {
            background-color: tomato;
            color: white;
            border: 1px solid rgb(255, 255, 255);
        }

        table {
            display: block;
            max-height: calc(100vh - 150px);
            overflow-y: scroll;
        }

        tr td:nth-child(1) {
            width: 100%;
        }

        .name-msg {
            display: block;
            padding: 10px;
            font-size: 1.2rem;
            font-weight: bold;
            text-align: center;
            color: tomato;
            border: 1px solid tomato;
            border-radius: 10px;
            margin: 10px auto;
        }
    </style>
</head>

<body>
    <div class="map-container">
        <div id="topbar">
            <h3><a href="/home" style="padding: 10px; text-decoration: none; font-family: monospace, Arial, Helvetica, sans-serif;">LOGO</a></h3>
            <form action="{{ route('update.circuit', ['id' => $circuit->id]) }}" method="post">
                @csrf
                <input type="text" name="id" id="id" hidden value="{{ $circuit->id }}">
                <div class="name-msg">Name: {{ $circuit->name }}</div>
                <input type="text" name="username" id="username" hidden value="{{ $username }}">
                <input type="text" name="coord" id="coord" hidden value="{{ $coordinates }}">
            </form>

            <div id="ciruit-map">
                <table id="circuit-history">
                </table>
            </div>
        </div>
        <div id="map"></div>
    </div>
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script>
        var map = L.map('map');

        var tunisiaBounds = [
            [37.3353, 8.240612],  
            [30.937179, 11.275852]  
        ];

        map.fitBounds(tunisiaBounds); 

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        let circuitHistory = document.getElementById('circuit-history');

        let markers = [];

        let markerCoordinates = [];

        function latLngToExtracting(data) {
            const result = data.map(item => {
                const matches = item.match(/LatLng\(([\d.]+),\s*([\d.]+)\)/);
                if (matches) {
                    return {
                        lat: parseFloat(matches[1]),
                        lng: parseFloat(matches[2])
                    };
                }
                return null;
            });

            return result;
        }

        document.addEventListener('DOMContentLoaded', function () {
            markers = [];
            markerCoordinates = [];

            let coords = document.getElementById('coord').value.split('; ');
            if (coords[0][0] != 'L') {
                tempCoords = [];
                coords.forEach(coord => {
                    tempCoords.push('LatLng(' + coord + ')');
                });
                coords = tempCoords;
            }
            let data = latLngToExtracting(coords);
            var nb = 0;
            data.forEach(({ lat, lng }) => {
                let newMarker = L.marker([lat, lng]).addTo(map);
                markers.push(newMarker);
                let newCoord = [lat, lng];
                markerCoordinates.push(newCoord);
                nb++;
                circuitHistory.innerHTML += `<tr><td data-coord="${[lat, lng]}">${nb}</td><td>${lat.toFixed(6)},<br>${lng.toFixed(5)}</td></tr>`;
            });
            if (markerCoordinates.length > 1) {
                let lineCoordinates = markerCoordinates.map(coord => [coord[0], coord[1]]);
                L.polyline(lineCoordinates, { color: 'blue' }).addTo(map);
            }

            console.log("markerCoordinates", markerCoordinates);
            console.log("markers", markers);
        });
    </script>
</body>

</html>