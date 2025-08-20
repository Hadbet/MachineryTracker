<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestión de Usuarios - Machinery Tracker</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
    <link rel="icon" href="images/Grammer_Logo.ico" type="image/x-icon">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white shadow-lg">
    <?php include 'estaticos/nav.php'; ?>
</header>

<!-- Main Content -->
<main class="container mx-auto p-4 md:p-8 flex-grow">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Create User Form -->
        <div class="lg:col-span-1">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-blue-600 pb-2 mb-6">Crear Nuevo
                    Usuario</h2>
                <form id="createUserForm">
                    <div class="space-y-4">
                        <div>
                            <label for="nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                            <input type="text" id="nombre" name="nombre" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        </div>
                        <div>
                            <label for="nomina" class="block text-sm font-medium text-gray-700">Nómina</label>
                            <input type="text" id="nomina" name="nomina" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        </div>
                        <div>
                            <label for="correo" class="block text-sm font-medium text-gray-700">Correo
                                Electrónico</label>
                            <input type="email" id="correo" name="correo" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                            <input type="password" id="password" name="password" required
                                   class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        </div>
                    </div>
                    <button type="submit"
                            class="mt-6 w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Crear Usuario
                    </button>
                    <div id="create-form-message" class="mt-4 text-center"></div>
                </form>
            </div>
        </div>

        <!-- Users Data Table -->
        <div class="lg:col-span-2">
            <div class="bg-white p-6 rounded-xl shadow-md">
                <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-blue-600 pb-2 mb-6">Usuarios Actuales</h2>
                <div class="overflow-x-auto">
                    <table id="usersTable" class="w-full text-sm text-left text-gray-500">
                        <thead class="text-xs text-white uppercase bg-gray-700">
                        <tr>
                            <th class="px-6 py-3">Nómina</th>
                            <th class="px-6 py-3">Nombre</th>
                            <th class="px-6 py-3">Rol</th>
                            <th class="px-6 py-3">Estatus</th>
                            <th class="px-6 py-3">Contraseña</th>
                            <th class="px-6 py-3">Acciones</th>
                        </tr>
                        </thead>
                        <tbody>
                        <!-- Rows will be inserted by JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Edit User Modal -->
<div id="editUserModal" class="modal fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center p-4">
    <div class="modal-content bg-white rounded-lg shadow-xl w-full max-w-lg p-6 transform scale-95">
        <div class="flex justify-between items-center border-b pb-3">
            <h2 class="text-2xl font-bold text-gray-800">Editar Usuario</h2>
            <button class="text-gray-500 hover:text-gray-800 text-3xl" onclick="closeEditModal()">&times;</button>
        </div>
        <form id="editUserForm" class="mt-4">
            <input type="hidden" id="edit_user_id" name="user_id">
            <div class="space-y-4">
                <div>
                    <label for="edit_nombre" class="block text-sm font-medium text-gray-700">Nombre Completo</label>
                    <input type="text" id="edit_nombre" name="nombre" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <div>
                    <label for="edit_nomina" class="block text-sm font-medium text-gray-700">Nómina</label>
                    <input type="text" id="edit_nomina" name="nomina" required readonly
                           class="mt-1 block w-full bg-gray-100 px-3 py-2 border border-gray-300 rounded-md">
                </div>
                <div>
                    <label for="edit_correo" class="block text-sm font-medium text-gray-700">Correo</label>
                    <input type="email" id="edit_correo" name="correo" required
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <div>
                    <label for="edit_rol" class="block text-sm font-medium text-gray-700">Rol</label>
                    <select id="edit_rol" name="rol" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        <option value="1">Usuario</option>
                        <option value="2">Admin</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div>
                    <label for="edit_estatus" class="block text-sm font-medium text-gray-700">Estatus</label>
                    <select id="edit_estatus" name="estatus" required
                            class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        <option value="1">Activo</option>
                        <option value="0">Inactivo</option>
                    </select>
                </div>
                <div>
                    <label for="edit_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña
                        (opcional)</label>
                    <input type="password" id="edit_password" name="password"
                           class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
            </div>
            <button type="submit"
                    class="mt-6 w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm font-medium text-white bg-green-600 hover:bg-green-700">
                Guardar Cambios
            </button>
            <div id="edit-form-message" class="mt-4 text-center"></div>
        </form>
    </div>
</div>

<?php include 'estaticos/piePagina.php'; ?>
<script src="js/usuario.js"></script>

</body>
</html>
