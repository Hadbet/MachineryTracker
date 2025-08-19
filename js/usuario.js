// --- CORRECTED: Mobile Menu Toggle ---
const mobileMenuButton = document.getElementById('mobile-menu-button');
const mobileMenu = document.getElementById('mobile-menu');
mobileMenuButton.addEventListener('click', () => {
    mobileMenu.classList.toggle('hidden');
});

// --- Create User Form Logic ---
const createUserForm = document.getElementById('createUserForm');
const createFormMessage = document.getElementById('create-form-message');

createUserForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(createUserForm);
    const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoCrearUsuario.php'; // URL de tu script PHP

    try {
        const response = await fetch(url, { method: 'POST', body: formData });
        const result = await response.json();

        if (result.success) {
            createFormMessage.innerHTML = `<p class="text-green-600">${result.message}</p>`;
            createUserForm.reset();
            loadUsers(); // Refresh the table
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        createFormMessage.innerHTML = `<p class="text-red-600">Error: ${error.message}</p>`;
    }
});

// --- Load Users Table Logic ---
async function loadUsers() {
    const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoConsultaUsuarios.php';
    try {
        const response = await fetch(url);
        const users = await response.json();
        const tableBody = document.querySelector("#usersTable tbody");
        tableBody.innerHTML = "";

        users.data.forEach(user => {
            const row = document.createElement('tr');
            row.className = "bg-white border-b hover:bg-gray-50";
            row.innerHTML = `
                        <td class="px-6 py-4">${user.Nomina}</td>
                        <td class="px-6 py-4 font-medium text-gray-900">${user.Nombre}</td>
                        <td class="px-6 py-4">${user.rol == '2' ? 'Admin' : 'Usuario'}</td>
                        <td class="px-6 py-4">${user.estatus == '1' ? '<span class="text-green-600 font-semibold">Activo</span>' : '<span class="text-red-600 font-semibold">Inactivo</span>'}</td>
                        <td class="px-6 py-4 font-mono">********</td>
                        <td class="px-6 py-4"><button class="px-3 py-1 bg-yellow-500 text-white rounded hover:bg-yellow-600">Editar</button></td>
                    `;
            row.querySelector('button').addEventListener('click', () => openEditModal(user));
            tableBody.appendChild(row);
        });
    } catch (error) {
        console.error("Error al cargar usuarios:", error);
    }
}

// --- Edit User Modal Logic ---
const editModal = document.getElementById('editUserModal');
const editUserForm = document.getElementById('editUserForm');
const editFormMessage = document.getElementById('edit-form-message');

function openEditModal(user) {
    editFormMessage.innerHTML = "";
    document.getElementById('edit_user_id').value = user.IdUsuario; // Assuming you have an ID
    document.getElementById('edit_nombre').value = user.Nombre;
    document.getElementById('edit_nomina').value = user.Nomina;
    document.getElementById('edit_correo').value = user.Correo;
    document.getElementById('edit_rol').value = user.rol;
    document.getElementById('edit_estatus').value = user.estatus;
    document.getElementById('edit_password').value = ""; // Clear password field
    editModal.classList.remove('hidden');
    editModal.classList.add('flex');
}

function closeEditModal() {
    editModal.classList.add('hidden');
    editModal.classList.remove('flex');
}

editUserForm.addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(editUserForm);
    const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoActualizarUsuario.php';

    try {
        const response = await fetch(url, { method: 'POST', body: formData });
        const result = await response.json();

        if (result.success) {
            editFormMessage.innerHTML = `<p class="text-green-600">${result.message}</p>`;
            setTimeout(() => {
                closeEditModal();
                loadUsers();
            }, 1500);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        editFormMessage.innerHTML = `<p class="text-red-600">Error: ${error.message}</p>`;
    }
});

// Initial load of users
document.addEventListener('DOMContentLoaded', loadUsers);