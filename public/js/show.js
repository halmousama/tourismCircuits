// Initialize the map
var map = L.map('map');

// Define the geographical bounds of Tunisia
var tunisiaBounds = [
    [37.3353, 8.240612],  // Southwest corner (approximate)
    [30.937179, 11.275852]  // Northeast corner (approximate)
];

// Set the view to fit the bounds of Tunisia
map.fitBounds(tunisiaBounds);

// Add the OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
}).addTo(map);

// Add a marker for a specific location
// var marker = L.marker([36.8065, 10.1815]).addTo(map);
// marker.bindPopup("<b>Tunis</b><br>City of khathra.").openPopup();

// // Add a circle to the map
// var circle = L.circle([48.8584, 2.2945], {
//     color: 'red',
//     fillColor: '#f03',
//     fillOpacity: 0.5,
//     radius: 500
// }).addTo(map);

// // Add a polygon to the map
// var polygon = L.polygon([
//     [48.86, 2.30],
//     [48.85, 2.30],
//     [48.85, 2.29]
// ]).addTo(map);

let circuitHistory = document.getElementById('circuit-history');

// Array to store markers
let markers = [];

// Array to store marker coordinates
let markerCoordinates = [];

function deleteCoord(nb) {
    // Remove the row from the table
    console.log('Deleting row:', nb);
    nb = parseInt(document.getElementById(nb).parentElement.parentElement.firstChild.innerHTML) - 1;
    var rows = circuitHistory.querySelectorAll("tr");
    rows[nb].remove();

    // Remove the corresponding marker from the map
    if (markers[nb]) {
        map.removeLayer(markers[nb]);
        markers.splice(nb, 1); // Remove the marker reference from the array
        markerCoordinates.splice(nb, 1); // Remove the marker coordinates

        // Update the hidden input field with the updated coordinates
        document.getElementById('coord').value = markerCoordinates.map(coord => coord.toString()).join('; ');

        // Redraw the polyline with the updated coordinates
        if (markerCoordinates.length > 1) {
            map.eachLayer(function (layer) {
                if (layer instanceof L.Polyline) {
                    map.removeLayer(layer);
                }
            });
            L.polyline(markerCoordinates, { color: 'blue' }).addTo(map);
        }
    }

    // Re-select rows after deletion
    rows = circuitHistory.querySelectorAll("tr");
    rows.forEach((row, index) => {
        row.firstChild.innerHTML = index + 1;
    });
}

// Display coordinates on click
// var popup = L.popup();
function onMapClick(e) {
    // popup
    //     .setLatLng(e.latlng)
    //     .setContent("You clicked the map at " + e.latlng.toString())
    //     .openOn(map);
    // console.log(e.latlng);
    // Create a marker at the clicked location
    const marker = L.marker(e.latlng).addTo(map);
    markers.push(marker); // Store the marker reference
    markerCoordinates.push([e.latlng.lat, e.latlng.lng]); // Store the marker coordinates

    console.log("markerCoordinates", markerCoordinates);

    // Update the hidden input field with the coordinates
    document.getElementById('coord').value = markerCoordinates.map(coord => coord.toString()).join('; ');

    // Draw a polyline connecting the markers
    if (markerCoordinates.length > 1) {
        map.eachLayer(function (layer) {
            if (layer instanceof L.Polyline) {
                map.removeLayer(layer);
            }
        });
        L.polyline(markerCoordinates, { color: 'blue' }).addTo(map);
    } else {
        map.eachLayer(function (layer) {
            if (layer instanceof L.Polyline) {
                map.removeLayer(layer);
            }
        });
    }

    // Existing code to add coordinate to the table
    var nb = document.querySelectorAll("#circuit-history tr").length + 1;
    circuitHistory.innerHTML += `<tr><td data-coord="${e.latlng.toString()}">${nb}</td><td style='width: 100%;'>${e.latlng.toString().slice(7, e.latlng.toString().indexOf(','))},<br>${e.latlng.toString().slice(e.latlng.toString().indexOf(',') + 1, e.latlng.toString().length - 2)}</td><td><button class="delete-coord" id="${nb}" onclick="deleteCoord(${nb})">X</button></td></tr>`;
}
map.on('click', onMapClick);

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

    console.log("result", result);
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
    console.log("coords", coords);
    let data = latLngToExtracting(coords);
    console.log("data", data);
    var nb = 0;
    data.forEach(({ lat, lng }) => {
        let newMarker = L.marker([lat, lng]).addTo(map);
        markers.push(newMarker);
        let newCoord = [lat, lng];
        markerCoordinates.push(newCoord);
        nb++;
        circuitHistory.innerHTML += `<tr><td data-coord="${[lat, lng]}">${nb}</td><td>${lat.toFixed(6)},<br>${lng.toFixed(5)}</td><td><button class="delete-coord" id="${nb}" onclick="deleteCoord(${nb})">X</button></td></tr>`;
    });
    if (markerCoordinates.length > 1) {
        let lineCoordinates = markerCoordinates.map(coord => [coord[0], coord[1]]);
        L.polyline(lineCoordinates, { color: 'blue' }).addTo(map);
    }

    console.log("markerCoordinates", markerCoordinates);
    console.log("markers", markers);
});

document.getElementById('update-coord').addEventListener('click', function (e) {
    e.preventDefault();
    let coords = markerCoordinates.map(coord => 'LatLng(' + coord[0] + ', ' + coord[1] + ')').join('; ');
    document.getElementById('coord').value = coords;
    console.log("coords", coords);
    setTimeout(function () {
        document.forms[0].submit();
    }, 1000);
});