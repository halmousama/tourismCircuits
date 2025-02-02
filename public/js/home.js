function circuitCard(circuit) {
    return `
        <div class="card" id="circuit-${circuit.id}">
            <button class="view-circuit" onclick="viewCircuit(${circuit.id})">View-Edit</button>
            <button class="export-circuit" onclick="exportCircuit(${circuit.id})">HTML</button>
            <button class="export-circuit" onclick="downloadCircuit(${circuit.id})">PDF</button>
            <button class="delete-circuit" onclick="deleteCircuit(${circuit.id})">X</button>
            <h3>${circuit.name}</h3>
            <h3>${new Intl.DateTimeFormat('en-GB', { year: 'numeric', month: 'long', day: '2-digit', hour: '2-digit', minute: '2-digit' }).format(new Date(circuit.updated_at))}</h3>
        </div>
    `;
}

function deleteCircuit(id) {
    const token = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

    fetch(`/api/circuits/${id}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': token,
            'Content-Type': 'application/json',
        }
    })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                const circuitCard = document.getElementById(`circuit-${id}`);
                if (circuitCard) {
                    circuitCard.remove();
                } else {
                    console.error('Circuit card not found in the UI');
                }
            } else {
                console.error('Error deleting circuit');
            }
        })
        .catch(error => console.error(error));
}

function viewCircuit(id) {
    window.location.href = `/view-circuit/${id}`;
}

function exportCircuit(id) {
    window.location.href = `/export-circuit/${id}`;
}

function downloadCircuit(id) {
    window.location.href = `/download-circuit/${id}`;
}

document.addEventListener('DOMContentLoaded', function () {
    const username = localStorage.getItem('username');

    if (username) {
        document.querySelector('.topbar .right').innerHTML = `
            <button class="logout" onclick="localStorage.clear(); window.location.href='/login'">Logout(${username})</button>
            <button class="new-map" onclick="window.location.href='/map'">+ New Map</button>`;
        fetch(`/api/circuits?username=${username}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    var mainContents = document.querySelector('.content');
                    data.circuits.forEach(circuit => {
                        mainContents.innerHTML += circuitCard(circuit);
                    })
                } else {
                    console.error('Error fetching circuits:', data.message);
                }
            })
            .catch(error => console.error('Fetch error:', error));
    } else {
        document.querySelector('.right').innerHTML = `<button class="login" onclick="window.location.href='/login'">Login</button>`;
        console.log('No username found in local storage.');
    }
});