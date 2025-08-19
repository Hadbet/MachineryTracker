<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perfil de Usuario - Machinery Tracker</title>
    <!-- Tailwind CSS -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="css/main.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex flex-col min-h-screen">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white shadow-lg">
    <?php include 'estaticos/nav.php'; ?>
</header>

<!-- Main Content -->
<main class="container mx-auto p-4 md:p-8 flex-grow flex items-center justify-center">
    <div class="w-full max-w-lg">
        <div class="bg-white p-8 rounded-xl shadow-md">
            <h2 class="text-2xl font-bold text-gray-800 text-center border-b-2 border-blue-600 pb-4 mb-6">Mi Perfil</h2>

            <!-- Change Password Form -->
            <form id="changePasswordForm">
                <h3 class="text-lg font-semibold text-gray-700 mb-4">Cambiar Contraseña</h3>
                <div class="space-y-4">
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Contraseña Actual</label>
                        <input type="password" id="current_password" name="current_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                    </div>
                    <div>
                        <label for="new_password" class="block text-sm font-medium text-gray-700">Nueva Contraseña</label>
                        <input type="password" id="new_password" name="new_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                    </div>
                    <div>
                        <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirmar Nueva Contraseña</label>
                        <input type="password" id="confirm_password" name="confirm_password" required class="mt-1 block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                    </div>
                </div>
                <button type="submit" id="submit-button" class="mt-6 w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                    Actualizar Contraseña
                </button>
                <div id="form-message" class="mt-4 text-center"></div>
            </form>
        </div>
    </div>
</main>

<!-- Footer -->
<footer class="bg-gray-800 text-gray-400 text-sm text-center py-4 mt-8">
    <div class="container mx-auto">
        <p>&copy; Grammer Automotive Puebla S.A de C.V</p>
        <p class="mt-1">
            Administrador: Ingenieria Liliana Sistos | Desarrollo por: Hadbet Ayari
        </p>
    </div>
</footer>

<script>
    const changePasswordForm = document.getElementById('changePasswordForm');
    const submitButton = document.getElementById('submit-button');
    const formMessage = document.getElementById('form-message');

    changePasswordForm.addEventListener('submit', async (e) => {
        e.preventDefault();

        const newPassword = document.getElementById('new_password').value;
        const confirmPassword = document.getElementById('confirm_password').value;

        // Client-side validation
        if (newPassword !== confirmPassword) {
            formMessage.innerHTML = '<p class="text-red-600">Las nuevas contraseñas no coinciden.</p>';
            return;
        }

        formMessage.textContent = '';
        submitButton.disabled = true;
        submitButton.textContent = 'Actualizando...';

        const formData = new FormData(changePasswordForm);
        // Replace with the URL of your PHP script for updating the password
        const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoActualizarContrasena.php';

        try {
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            const result = await response.json();

            if (result.success) {
                formMessage.innerHTML = `<p class="text-green-600">${result.message}</p>`;
                changePasswordForm.reset();
            } else {
                throw new Error(result.message);
            }
        } catch (error) {
            formMessage.innerHTML = `<p class="text-red-600">Error: ${error.message}</p>`;
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Actualizar Contraseña';
        }
    });
</script>

</body>
</html>
