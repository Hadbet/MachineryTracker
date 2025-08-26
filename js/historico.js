// Store all records globally to filter from memory
let allRecords = [];

// --- Function to fetch and display initial data ---
async function loadResults() {
    // Replace with your actual PHP script URL
    const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoConsultaMaquinaria.php';
    try {
        const response = await fetch(url);
        if (!response.ok) throw new Error('Network response was not ok.');
        const result = await response.json();
        allRecords = result.data; // Assuming your PHP returns { "data": [...] }
        displayRecords(allRecords);
    } catch (error) {
        console.error('Error fetching data:', error);
        const tableBody = document.querySelector("#resultsTable tbody");
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4">Error al cargar los datos.</td></tr>`;
    }
}

// --- Function to render records in the table ---
function displayRecords(records) {
    const tableBody = document.querySelector("#resultsTable tbody");
    tableBody.innerHTML = ""; // Clear existing rows
    if (records.length === 0) {
        tableBody.innerHTML = `<tr><td colspan="6" class="text-center py-4">No se encontraron registros.</td></tr>`;
        return;
    }
    records.forEach(record => {
        const row = document.createElement('tr');
        row.className = "bg-white border-b hover:bg-gray-50";
        row.innerHTML = `
                    <td class="px-6 py-4 font-medium text-gray-900">${record.Proyecto}</td>
                    <td class="px-6 py-4">${record.NombreEstacion}</td>
                    <td class="px-6 py-4">${record.Responsable}</td>
                    <td class="px-6 py-4">${record.Empresa}</td>
                    <td class="px-6 py-4">${new Date(record.Fecha).toLocaleDateString()}</td>
                    <td class="px-6 py-4"><button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Details</button></td>
                `;
        row.querySelector('button').addEventListener('click', () => viewDetails(record));
        tableBody.appendChild(row);
    });
}

// --- Filtering Logic ---
function applyFilters() {
    const filterProyecto = document.getElementById('filterProyecto').value.toLowerCase();
    const filterEstacion = document.getElementById('filterEstacion').value.toLowerCase();
    const filterResponsable = document.getElementById('filterResponsable').value.toLowerCase();
    const filterEmpresa = document.getElementById('filterEmpresa').value.toLowerCase();
    const filterFecha = document.getElementById('filterFecha').value;

    const filteredRecords = allRecords.filter(record => {
        const recordDate = record.Fecha.split(' ')[0]; // Get only the date part YYYY-MM-DD
        return (record.Proyecto.toLowerCase().includes(filterProyecto)) &&
            (record.NombreEstacion.toLowerCase().includes(filterEstacion)) &&
            (record.Responsable.toLowerCase().includes(filterResponsable)) &&
            (record.Empresa.toLowerCase().includes(filterEmpresa)) &&
            (filterFecha === '' || recordDate === filterFecha);
    });
    displayRecords(filteredRecords);
}

// Add event listeners to filter inputs
['filterProyecto', 'filterEstacion', 'filterResponsable', 'filterEmpresa', 'filterFecha'].forEach(id => {
    document.getElementById(id).addEventListener('input', applyFilters);
});

// --- Modal Logic ---
function viewDetails(record) {
    const modal = document.getElementById('detailsModal');
    const detailsContainer = document.getElementById('details-content');

    // Base URL for images, adjust if necessary
    const baseURL = 'dao/evidencias/';

    const renderImage = (path, folder) => {
        if (!path) {
            return '<div class="detail-image flex items-center justify-center bg-gray-200 text-gray-500">No disponible</div>';
        }
        const fullPath = `${baseURL}${folder}/${path}`;
        return `<img src="${fullPath}" alt="Evidencia" class="detail-image" onclick="expandImage('${fullPath}')">`;
    };

    detailsContainer.innerHTML = `
                <div class="details-grid">
                    <div class="detail-item"><dt>Proyecto</dt><dd>${record.Proyecto}</dd></div>
                    <div class="detail-item"><dt>Nombre de Estación</dt><dd>${record.NombreEstacion}</dd></div>
                    <div class="detail-item"><dt>Responsable</dt><dd>${record.Responsable}</dd></div>
                    <div class="detail-item"><dt>Activo Fijo</dt><dd>${record.ActivoFijo || 'N/A'}</dd></div>
                    <div class="detail-item"><dt>Disposición</dt><dd>${record.Disposicion}</dd></div>
                    <div class="detail-item"><dt>Fecha de Registro</dt><dd>${new Date(record.Fecha).toLocaleString()}</dd></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mt-6 mb-4 border-b pb-2">Contacto Externo</h3>
                <div class="details-grid">
                    <div class="detail-item"><dt>Empresa</dt><dd>${record.Empresa}</dd></div>
                    <div class="detail-item"><dt>Nombre</dt><dd>${record.NombreExterno}</dd></div>
                    <div class="detail-item"><dt>Teléfono</dt><dd>${record.NumeroExterno}</dd></div>
                    <div class="detail-item md:col-span-2"><dt>Dirección</dt><dd>${record.DireccionExterno}</dd></div>
                </div>
                <h3 class="text-xl font-bold text-gray-800 mt-6 mb-4 border-b pb-2">Evidencias Fotográficas</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <div><p class="font-semibold mb-2 text-center">Imagen Estación</p>${renderImage(record.ImagenEstacion, 'estacion')}</div>
                    <div><p class="font-semibold mb-2 text-center">Evidencia Salida</p>${renderImage(record.ImagenSalida, 'salidas')}</div>
                    <div><p class="font-semibold mb-2 text-center">Evidencia Entrada</p>${renderImage(record.ImagenEntrada, 'entradas')}</div>
                    <div><p class="font-semibold mb-2 text-center">Evidencia Resguardo</p>${renderImage(record.ImagenEvidenciaResguardo, 'resguardo')}</div>
                </div>
            `;

    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModal() {
    const modal = document.getElementById('detailsModal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}

// --- NEW: Image Viewer Logic ---
function expandImage(src) {
    document.getElementById('fullScreenImage').src = src;
    document.getElementById('imageViewerModal').classList.remove('hidden');
}

function closeImageViewer() {
    document.getElementById('imageViewerModal').classList.add('hidden');
}

// --- Export to CSV Logic ---
function exportTableToCSV(filename) {
    const table = document.getElementById("resultsTable");
    let csv = [];
    const headers = Array.from(table.querySelectorAll("thead th")).map(header => header.innerText).slice(0, -1);
    csv.push(headers.join(','));
    const rows = table.querySelectorAll("tbody tr");
    for (const row of rows) {
        const cols = Array.from(row.querySelectorAll("td")).map(cell => `"${cell.innerText.replace(/"/g, '""')}"`).slice(0, -1);
        csv.push(cols.join(','));
    }
    downloadCSV(csv.join('\n'), filename);
}

function downloadCSV(csv, filename) {
    const blob = new Blob(["\uFEFF" + csv], { type: 'text/csv;charset=utf-8;' });
    const link = document.createElement("a");
    const url = URL.createObjectURL(blob);
    link.setAttribute("href", url);
    link.setAttribute("download", filename);
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Load initial results when the page is ready
document.addEventListener('DOMContentLoaded', loadResults);