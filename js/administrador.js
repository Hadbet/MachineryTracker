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
    const form = document.getElementById('editForm');
    form.reset(); // Reset form fields and file inputs

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

// --- Function to handle preview updates in the modal ---
function setupModalImagePreviews() {
    const previews = [
        { inputId: 'edit_input_imagen_estacion', previewId: 'preview_edit_imagen_estacion' },
        { inputId: 'edit_input_evidencia_salida', previewId: 'preview_edit_evidencia_salida' },
        { inputId: 'edit_input_evidencia_entrada', previewId: 'preview_edit_evidencia_entrada' },
        { inputId: 'edit_input_evidencia_resguardo', previewId: 'preview_edit_evidencia_resguardo' }
    ];

    previews.forEach(item => {
        const input = document.getElementById(item.inputId);
        const preview = document.getElementById(item.previewId);
        input.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Nueva vista previa">`;
                };
                reader.readAsDataURL(file);
            }
        });
    });
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

document.addEventListener('DOMContentLoaded', () => {
    loadResults();
    setupModalImagePreviews(); // Call the function to set up listeners
});