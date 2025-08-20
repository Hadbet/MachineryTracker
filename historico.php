<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Estaciones - Machinery Tracker</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link rel="icon" href="images/Grammer_Logo.ico" type="image/x-icon">
</head>
<body class="bg-gray-100">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white shadow-lg">
    <?php include 'estaticos/nav.php'; ?>
</header>

<!-- Main Content -->
<main class="container mx-auto p-4 md:p-8">
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-md max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-gray-800">Histórico de Registros</h2>
            <button onclick="exportTableToCSV('historico_maquinaria.csv')"
                    class="mt-4 md:mt-0 w-full md:w-auto px-4 py-2 bg-blue-600 text-white font-semibold rounded-lg hover:bg-blue-700 transition-colors">
                Exportar a Excel (CSV)
            </button>
        </div>

        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
            <input type="text" id="filterProyecto" placeholder="Filtrar por Proyecto..."
                   class="px-3 py-2 border border-gray-300 rounded-md">
            <input type="text" id="filterEstacion" placeholder="Filtrar por Estación..."
                   class="px-3 py-2 border border-gray-300 rounded-md">
            <input type="text" id="filterResponsable" placeholder="Filtrar por Responsable..."
                   class="px-3 py-2 border border-gray-300 rounded-md">
            <input type="text" id="filterEmpresa" placeholder="Filtrar por Empresa..."
                   class="px-3 py-2 border border-gray-300 rounded-md">
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
<?php include 'estaticos/piePagina.php'; ?>

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

<script src="js/historico.js"></script>

</body>
</html>
