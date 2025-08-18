<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Histórico - Machinery Tracker</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #f0f4f8; }
        .modal { transition: opacity 0.25s ease; }
        .modal-content { transition: transform 0.25s ease; }
        .details-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem; }
        .detail-item { background-color: #f9fafb; padding: 0.75rem; border-radius: 0.5rem; border: 1px solid #e5e7eb; transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out; }
        .detail-item:hover { transform: translateY(-2px); box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .detail-item dt { font-weight: 600; color: #4b5563; }
        .detail-item dd { color: #1f2937; margin-top: 0.25rem; }
        .detail-image { width: 100%; height: 200px; object-fit: cover; border-radius: 0.5rem; border: 1px solid #e5e7eb; cursor: pointer; transition: transform 0.2s ease-in-out; }
        .detail-image:hover { transform: scale(1.05); }
        .image-viewer { position: fixed; inset: 0; background-color: rgba(0,0,0,0.75); z-index: 1001; display: flex; align-items: center; justify-content: center; cursor: pointer; }
        .image-viewer img { max-width: 90vw; max-height: 90vh; border-radius: 8px; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    </style>
</head>
<body class="bg-gray-100">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white shadow-lg">
    <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
        <div class="flex items-center">
            <img src="https://placehold.co/40x40/ffffff/334155?text=G" alt="Logo de Grammer" class="h-10 w-10 mr-3 rounded-full">
            <h1 class="text-xl font-bold">Machinery Tracker</h1>
        </div>
        <div class="hidden md:flex items-center space-x-6">
            <a href="inicio.php" class="hover:text-blue-300 transition-colors">Inicio</a>
            <a href="#" class="hover:text-blue-300 transition-colors">Pendientes</a>
            <a href="#" class="text-blue-300 font-semibold border-b-2 border-blue-300">Histórico</a>
            <a href="#" class="hover:text-blue-300 transition-colors">Perfil</a>
        </div>
    </nav>
</header>

<!-- Main Content -->
<main class="container mx-auto p-4 md:p-8">
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-md max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Histórico de Registros</h2>
            <button onclick="exportTableToCSV('historico_maquinaria.csv')" class="mt-4 md:mt-0 w-full md:w-auto px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">Exportar a Excel (CSV)</button>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <input type="text" id="filterProyecto" placeholder="Filtrar por Proyecto..." class="px-3 py-2 border border-gray-300 rounded-md">
            <input type="text" id="filterEstacion" placeholder="Filtrar por Estación..." class="px-3 py-2 border border-gray-300 rounded-md">
            <input type="text" id="filterResponsable" placeholder="Filtrar por Responsable..." class="px-3 py-2 border border-gray-300 rounded-md">
            <input type="text" id="filterEmpresa" placeholder="Filtrar por Empresa..." class="px-3 py-2 border border-gray-300 rounded-md">
            <input type="date" id="filterFecha" class="px-3 py-2 border border-gray-300 rounded-md">
        </div>

        <!-- Data Table -->
        <div class="overflow-x-auto">
            <table id="resultsTable" class="w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-white uppercase bg-gray-700">
                <tr>
                    <th scope="col" class="px-6 py-3">Proyecto</th>
                    <th scope="col" class="px-6 py-3">Estación</th>
                    <th scope="col" class="px-6 py-3">Responsable</th>
                    <th scope="col" class="px-6 py-3">Empresa</th>
                    <th scope="col" class="px-6 py-3">Fecha</th>
                    <th scope="col" class="px-6 py-3">Acciones</th>
                </tr>
                </thead>
                <tbody>
                <!-- Rows will be inserted here by JavaScript -->
                </tbody>
            </table>
        </div>
    </div>
</main>

<!-- The Modal for Details -->
<div id="detailsModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto p-6 transform scale-95">
        <div class="flex justify-between items-center border-b pb-3">
            <h2 class="text-2xl font-bold text-gray-800">Detalles del Registro</h2>
            <button class="text-gray-500 hover:text-gray-800 text-3xl" onclick="closeModal()">&times;</button>
        </div>
        <div id="details-content" class="mt-4">
            <!-- Details will be injected here -->
        </div>
    </div>
</div>

<!-- NEW: Modal for Image Viewer -->
<div id="imageViewerModal" class="image-viewer hidden" onclick="closeImageViewer()">
    <img id="fullScreenImage" src="" alt="Vista ampliada">
</div>

<script>
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
                    <td class="px-6 py-4"><button class="px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Detalles</button></td>
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
</script>

</body>
</html>
