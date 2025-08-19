<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin: Editar Maquinaria - Machinery Tracker</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white shadow-lg">
    <?php include 'estaticos/nav.php'; ?>
</header>

<!-- Main Content -->
<main class="container mx-auto p-4 md:p-8">
    <div class="bg-white p-6 md:p-8 rounded-xl shadow-md max-w-7xl mx-auto">
        <h2 class="text-2xl font-bold text-gray-800 mb-6">Administrar Registros de Maquinaria</h2>

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
                    <th scope="col" class="px-6 py-3">Disposición</th>
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

<!-- The Modal for Editing -->
<div id="editModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto transform scale-95">
        <form id="editForm">
            <div class="p-6">
                <div class="flex justify-between items-center border-b pb-3">
                    <h2 class="text-2xl font-bold text-gray-800">Editar Registro</h2>
                    <button type="button" class="text-gray-500 hover:text-gray-800 text-3xl" onclick="closeModal()">&times;</button>
                </div>

                <input type="hidden" id="edit_IdMaquinaria" name="IdMaquinaria">

                <!-- Form sections -->
                <div class="mt-4 space-y-6">
                    <!-- Station Data -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Datos de la Estación</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input name="proyecto" id="edit_proyecto" placeholder="Proyecto" class="px-3 py-2 border rounded">
                            <input name="nombre_estacion" id="edit_nombre_estacion" placeholder="Nombre Estación" class="px-3 py-2 border rounded">
                            <input name="responsable" id="edit_responsable" placeholder="Responsable" class="px-3 py-2 border rounded">
                            <input name="activo_fijo" id="edit_activo_fijo" placeholder="Activo Fijo (Opcional)" class="px-3 py-2 border rounded">
                            <select name="disposicion" id="edit_disposicion" class="px-3 py-2 border rounded">
                                <option value="en_uso">En uso</option>
                                <option value="resguardo">Resguardo</option>
                                <option value="scrap">Scrap</option>
                                <option value="modificacion">En modificación</option>
                            </select>
                        </div>
                    </div>

                    <!-- External Contact -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Contacto Externo</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <input name="contacto_nombre" id="edit_contacto_nombre" placeholder="Nombre Contacto" class="px-3 py-2 border rounded">
                            <select name="contacto_empresa" id="edit_contacto_empresa" class="px-3 py-2 border rounded">
                                <option value="GXIN">GXIN</option>
                                <option value="QRO-3330">QRO-3330</option>
                                <option value="TET-3310">TET-3310</option>
                            </select>
                            <input name="contacto_telefono" id="edit_contacto_telefono" placeholder="Teléfono" class="px-3 py-2 border rounded">
                            <textarea name="contacto_direccion" id="edit_contacto_direccion" placeholder="Dirección" rows="2" class="md:col-span-2 px-3 py-2 border rounded"></textarea>
                        </div>
                    </div>

                    <!-- Evidences -->
                    <div>
                        <h3 class="text-lg font-semibold text-gray-700 mb-4">Evidencias Fotográficas (subir para reemplazar)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                            <div>
                                <label class="font-medium text-sm">Imagen Estación</label>
                                <div id="preview_edit_imagen_estacion" class="image-preview mt-1"></div>
                                <input type="file" name="imagen_estacion" class="mt-2 text-xs">
                            </div>
                            <div id="container_edit_salida">
                                <label class="font-medium text-sm">Evidencia Salida</label>
                                <div id="preview_edit_evidencia_salida" class="image-preview mt-1"></div>
                                <input type="file" name="evidencia_salida" class="mt-2 text-xs">
                            </div>
                            <div id="container_edit_entrada">
                                <label class="font-medium text-sm">Evidencia Entrada</label>
                                <div id="preview_edit_evidencia_entrada" class="image-preview mt-1"></div>
                                <input type="file" name="evidencia_entrada" class="mt-2 text-xs">
                            </div>
                            <div id="container_edit_resguardo">
                                <label class="font-medium text-sm">Evidencia Resguardo</label>
                                <div id="preview_edit_evidencia_resguardo" class="image-preview mt-1"></div>
                                <input type="file" name="evidencia_resguardo" class="mt-2 text-xs">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Modal Footer -->
            <div class="bg-gray-50 px-6 py-3 flex justify-end">
                <button type="submit" class="px-4 py-2 bg-green-600 text-white font-semibold rounded-lg hover:bg-green-700">Guardar Cambios</button>
            </div>
        </form>
    </div>
</div>


<script>
    let allRecords = [];
    const baseURL = 'dao/evidencias/'; // Base URL for images

    // --- Load and Display Data ---
    async function loadResults() {
        const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoConsultaMaquinaria.php';
        try {
            const response = await fetch(url);
            const result = await response.json();
            allRecords = result.data;
            displayRecords(allRecords);
        } catch (error) {
            console.error('Error fetching data:', error);
        }
    }

    function displayRecords(records) {
        const tableBody = document.querySelector("#resultsTable tbody");
        tableBody.innerHTML = "";
        records.forEach(record => {
            const row = document.createElement('tr');
            row.className = "bg-white border-b hover:bg-gray-50";
            row.innerHTML = `
                <td class="px-6 py-4">${record.Proyecto}</td>
                <td class="px-6 py-4">${record.NombreEstacion}</td>
                <td class="px-6 py-4">${record.Responsable}</td>
                <td class="px-6 py-4">${record.Disposicion}</td>
                <td class="px-6 py-4">${new Date(record.Fecha).toLocaleDateString()}</td>
                <td class="px-6 py-4"><button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Editar</button></td>
            `;
            row.querySelector('button').addEventListener('click', () => openEditModal(record));
            tableBody.appendChild(row);
        });
    }

    // --- Filtering Logic ---
    function applyFilters() {
        const filters = {
            proyecto: document.getElementById('filterProyecto').value.toLowerCase(),
            estacion: document.getElementById('filterEstacion').value.toLowerCase(),
            responsable: document.getElementById('filterResponsable').value.toLowerCase(),
            empresa: document.getElementById('filterEmpresa').value.toLowerCase(),
            fecha: document.getElementById('filterFecha').value
        };
        const filteredRecords = allRecords.filter(r =>
            r.Proyecto.toLowerCase().includes(filters.proyecto) &&
            r.NombreEstacion.toLowerCase().includes(filters.estacion) &&
            r.Responsable.toLowerCase().includes(filters.responsable) &&
            r.Empresa.toLowerCase().includes(filters.empresa) &&
            (filters.fecha === '' || r.Fecha.startsWith(filters.fecha))
        );
        displayRecords(filteredRecords);
    }
    ['filterProyecto', 'filterEstacion', 'filterResponsable', 'filterEmpresa', 'filterFecha'].forEach(id => {
        document.getElementById(id).addEventListener('input', applyFilters);
    });

    // --- Edit Modal Logic ---
    function openEditModal(record) {
        const modal = document.getElementById('editModal');
        // Populate form
        document.getElementById('edit_IdMaquinaria').value = record.IdMaquinaria;
        document.getElementById('edit_proyecto').value = record.Proyecto;
        document.getElementById('edit_nombre_estacion').value = record.NombreEstacion;
        document.getElementById('edit_responsable').value = record.Responsable;
        document.getElementById('edit_activo_fijo').value = record.ActivoFijo;
        document.getElementById('edit_disposicion').value = record.Disposicion;
        document.getElementById('edit_contacto_nombre').value = record.NombreExterno;
        document.getElementById('edit_contacto_empresa').value = record.Empresa;
        document.getElementById('edit_contacto_telefono').value = record.NumeroExterno;
        document.getElementById('edit_contacto_direccion').value = record.DireccionExterno;

        // Populate image previews
        const setPreview = (id, folder, filename) => {
            const el = document.getElementById(id);
            if (filename) {
                el.innerHTML = `<img src="${baseURL}${folder}/${filename}" alt="Vista previa">`;
            } else {
                el.innerHTML = '<span>No hay imagen</span>';
            }
        };
        setPreview('preview_edit_imagen_estacion', 'estacion', record.ImagenEstacion);
        setPreview('preview_edit_evidencia_salida', 'salidas', record.ImagenSalida);
        setPreview('preview_edit_evidencia_entrada', 'entradas', record.ImagenEntrada);
        setPreview('preview_edit_evidencia_resguardo', 'resguardo', record.ImagenEvidenciaResguardo);

        // Handle dynamic fields based on disposition
        handleDisposicionChangeInModal();
        document.getElementById('edit_disposicion').addEventListener('change', handleDisposicionChangeInModal);

        modal.classList.remove('hidden');
        modal.classList.add('flex');
    }

    function closeModal() {
        document.getElementById('editModal').classList.add('hidden');
        document.getElementById('editModal').classList.remove('flex');
    }

    function handleDisposicionChangeInModal() {
        const value = document.getElementById('edit_disposicion').value;
        const containers = {
            salida: document.getElementById('container_edit_salida'),
            entrada: document.getElementById('container_edit_entrada'),
            resguardo: document.getElementById('container_edit_resguardo')
        };
        Object.values(containers).forEach(c => c.style.display = 'none');
        if (value === 'resguardo' || value === 'modificacion') {
            Object.values(containers).forEach(c => c.style.display = 'block');
        } else if (value === 'scrap') {
            containers.salida.style.display = 'block';
        }
    }

    // --- Form Submission Logic ---
    document.getElementById('editForm').addEventListener('submit', async (e) => {
        e.preventDefault();
        const formData = new FormData(e.target);
        const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoActualziarMaquinaria.php';

        try {
            const response = await fetch(url, { method: 'POST', body: formData });
            const result = await response.json();
            if (result.success) {
                alert('Registro actualizado exitosamente.');
                closeModal();
                loadResults(); // Refresh table
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            alert(`Error al actualizar: ${error.message}`);
        }
    });

    document.addEventListener('DOMContentLoaded', loadResults);
</script>

</body>
</html>
