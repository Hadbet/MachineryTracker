<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Machinery Tracker</title>
    <!-- Tailwind CSS for styling -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Google Fonts: Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        /* Custom styles to enhance Tailwind */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f0f4f8; /* Light blue-gray background */
        }
        /* Custom focus ring color */
        .focus-ring-grammer:focus {
            --tw-ring-opacity: 1;
            --tw-ring-color: #3b82f6; /* A nice blue color for focus */
            box-shadow: 0 0 0 2px var(--tw-ring-color);
        }
        /* Style for image preview containers */
        .image-preview {
            width: 100%;
            height: 150px;
            border: 2px dashed #9ca3af;
            border-radius: 0.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f9fafb;
            color: #6b7280;
            font-weight: 500;
            overflow: hidden; /* Ensures the image fits within the rounded corners */
        }
        .image-preview img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
    </style>
</head>
<body class="bg-gray-100">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white shadow-lg">
    <nav class="container mx-auto px-6 py-3 flex justify-between items-center">
        <!-- Logo and Portal Title -->
        <div class="flex items-center">
            <img src="images/logoWhite.png" alt="Logo de Grammer" class="h-10 w-10 mr-3 rounded-full">
            <h1 class="text-xl font-bold">Machinery Tracker</h1>
        </div>

        <!-- Desktop Menu -->
        <div class="hidden md:flex items-center space-x-6">
            <a href="#" class="hover:text-blue-300 transition-colors">Inicio</a>
            <a href="#" class="hover:text-blue-300 transition-colors">Pendientes</a>
            <a href="#" class="hover:text-blue-300 transition-colors">Histórico</a>
            <a href="#" class="hover:text-blue-300 transition-colors">Perfil</a>
        </div>

        <!-- Mobile Menu Button -->
        <div class="md:hidden">
            <button id="mobile-menu-button" class="text-white focus:outline-none">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                </svg>
            </button>
        </div>
    </nav>
    <!-- Mobile Menu -->
    <div id="mobile-menu" class="hidden md:hidden px-6 pb-4">
        <a href="#" class="block py-2 hover:text-blue-300">Inicio</a>
        <a href="#" class="block py-2 hover:text-blue-300">Pendientes</a>
        <a href="#" class="block py-2 hover:text-blue-300">Histórico</a>
        <a href="#" class="block py-2 hover:text-blue-300">Perfil</a>
    </div>
</header>

<!-- Main Content -->
<main class="container mx-auto p-4 md:p-8">
    <form id="stationForm" class="bg-white p-6 md:p-8 rounded-xl shadow-md max-w-4xl mx-auto">
        <!-- Section 1: Station Data -->
        <div class="mb-8">
            <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-blue-600 pb-2 mb-6">Datos de la Estación</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Project -->
                <div>
                    <label for="proyecto" class="block text-sm font-medium text-gray-700 mb-1">Proyecto <span class="text-red-500">*</span></label>
                    <input type="text" id="proyecto" name="proyecto" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Station Name -->
                <div>
                    <label for="nombre_estacion" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Estación <span class="text-red-500">*</span></label>
                    <input type="text" id="nombre_estacion" name="nombre_estacion" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Responsible Person -->
                <div>
                    <label for="responsable" class="block text-sm font-medium text-gray-700 mb-1">Responsable <span class="text-red-500">*</span></label>
                    <input type="text" id="responsable" name="responsable" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Fixed Asset (Optional) -->
                <div>
                    <label for="activo_fijo" class="block text-sm font-medium text-gray-700 mb-1">Activo Fijo (Opcional)</label>
                    <input type="text" id="activo_fijo" name="activo_fijo" class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Station Image -->
                <div class="md:col-span-2">
                    <label for="imagen_estacion" class="block text-sm font-medium text-gray-700 mb-1">Imagen de la Estación <span class="text-red-500">*</span></label>
                    <input type="file" id="imagen_estacion" name="imagen_estacion" required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="preview_imagen_estacion" class="image-preview mt-2"><span>Vista Previa</span></div>
                </div>
                <!-- Disposition -->
                <div class="md:col-span-2">
                    <label for="disposicion" class="block text-sm font-medium text-gray-700 mb-1">Disposición <span class="text-red-500">*</span></label>
                    <select id="disposicion" name="disposicion" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        <option value="en_uso">En uso</option>
                        <option value="resguardo">Resguardo</option>
                        <option value="scrap">Scrap</option>
                        <option value="modificacion">En modificación</option>
                    </select>
                </div>
                <!-- Exit Evidence -->
                <div id="container_evidencia_salida">
                    <label for="evidencia_salida" class="block text-sm font-medium text-gray-700 mb-1">Evidencia de Salida <span class="text-red-500">*</span></label>
                    <input type="file" id="evidencia_salida" name="evidencia_salida" required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="preview_evidencia_salida" class="image-preview mt-2"><span>Vista Previa</span></div>
                </div>
                <!-- Entry Evidence (Optional) -->
                <div id="container_evidencia_entrada">
                    <label for="evidencia_entrada" class="block text-sm font-medium text-gray-700 mb-1">Evidencia de Entrada (Opcional)</label>
                    <input type="file" id="evidencia_entrada" name="evidencia_entrada" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="preview_evidencia_entrada" class="image-preview mt-2"><span>Vista Previa</span></div>
                </div>
                <!-- Custody Evidence -->
                <div id="container_evidencia_resguardo">
                    <label for="evidencia_resguardo" class="block text-sm font-medium text-gray-700 mb-1">Evidencia de Resguardo <span class="text-red-500">*</span></label>
                    <input type="file" id="evidencia_resguardo" name="evidencia_resguardo" required accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="preview_evidencia_resguardo" class="image-preview mt-2"><span>Vista Previa</span></div>
                </div>
            </div>
        </div>

        <!-- Section 2: External Company Contact -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-blue-600 pb-2 mb-6">Contacto de Empresa Externa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contact Name -->
                <div>
                    <label for="contacto_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span class="text-red-500">*</span></label>
                    <input type="text" id="contacto_nombre" name="contacto_nombre" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Company Name -->
                <div>
                    <label for="contacto_empresa" class="block text-sm font-medium text-gray-700 mb-1">Empresa <span class="text-red-500">*</span></label>
                    <select id="contacto_empresa" name="contacto_empresa" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        <option value="GXIN">GXIN</option>
                        <option value="QRO-3330">QRO-3330</option>
                        <option value="TET-3310">TET-3310</option>
                    </select>
                </div>
                <!-- Phone Number -->
                <div>
                    <label for="contacto_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono <span class="text-red-500">*</span></label>
                    <input type="tel" id="contacto_telefono" name="contacto_telefono" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="contacto_direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección <span class="text-red-500">*</span></label>
                    <textarea id="contacto_direccion" name="contacto_direccion" rows="3" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="contacto_correo" class="block text-sm font-medium text-gray-700 mb-1">Correo <span class="text-red-500">*</span></label>
                    <input type="text" id="contacto_correo" name="contacto_correo" required class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" id="submit-button" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:scale-105">
                Registrar Estación
            </button>
        </div>

        <!-- Message area for success or error -->
        <div id="form-message" class="mt-4 text-center"></div>
    </form>
</main>

<script>
    // --- Mobile Menu Toggle ---
    const mobileMenuButton = document.getElementById('mobile-menu-button');
    const mobileMenu = document.getElementById('mobile-menu');
    mobileMenuButton.addEventListener('click', () => {
        mobileMenu.classList.toggle('hidden');
    });

    // --- Image Preview Functionality ---
    function setupImagePreview(inputId, previewId) {
        const input = document.getElementById(inputId);
        const preview = document.getElementById(previewId);
        input.addEventListener('change', (event) => {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    preview.innerHTML = `<img src="${e.target.result}" alt="Vista previa">`;
                };
                reader.readAsDataURL(file);
            } else {
                preview.innerHTML = '<span>Vista Previa</span>';
            }
        });
    }
    setupImagePreview('imagen_estacion', 'preview_imagen_estacion');
    setupImagePreview('evidencia_salida', 'preview_evidencia_salida');
    setupImagePreview('evidencia_entrada', 'preview_evidencia_entrada');
    setupImagePreview('evidencia_resguardo', 'preview_evidencia_resguardo');

    // --- NEW: Dynamic Form Fields based on Disposition ---
    const disposicionSelect = document.getElementById('disposicion');
    const containerSalida = document.getElementById('container_evidencia_salida');
    const containerEntrada = document.getElementById('container_evidencia_entrada');
    const containerResguardo = document.getElementById('container_evidencia_resguardo');
    const inputSalida = document.getElementById('evidencia_salida');
    const inputEntrada = document.getElementById('evidencia_entrada');
    const inputResguardo = document.getElementById('evidencia_resguardo');

    function handleDisposicionChange() {
        const value = disposicionSelect.value;

        // Reset all to hidden and not required by default
        [containerSalida, containerEntrada, containerResguardo].forEach(c => c.style.display = 'none');
        [inputSalida, inputEntrada, inputResguardo].forEach(i => i.required = false);

        switch (value) {
            case 'en_uso':
                // No evidence needed
                break;
            case 'resguardo':
                // All evidence needed
                [containerSalida, containerEntrada, containerResguardo].forEach(c => c.style.display = 'block');
                [inputSalida, inputResguardo].forEach(i => i.required = true); // Entrada is optional
                break;
            case 'scrap':
                // Only exit evidence
                containerSalida.style.display = 'block';
                inputSalida.required = true;
                break;
            case 'modificacion':
                // All evidence needed
                [containerSalida, containerEntrada, containerResguardo].forEach(c => c.style.display = 'block');
                [inputSalida, inputResguardo].forEach(i => i.required = true); // Entrada is optional
                break;
        }
    }
    disposicionSelect.addEventListener('change', handleDisposicionChange);
    // Initial call to set the correct state on page load
    handleDisposicionChange();


    // --- Image Resizing Functionality ---
    function resizeImage(file, options = { maxWidth: 1024, maxHeight: 1024, quality: 0.7 }) {
        return new Promise((resolve, reject) => {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            reader.onload = (event) => {
                const img = new Image();
                img.src = event.target.result;
                img.onload = () => {
                    const canvas = document.createElement('canvas');
                    let { width, height } = img;
                    if (width > height) {
                        if (width > options.maxWidth) {
                            height *= options.maxWidth / width;
                            width = options.maxWidth;
                        }
                    } else {
                        if (height > options.maxHeight) {
                            width *= options.maxHeight / height;
                            height = options.maxHeight;
                        }
                    }
                    canvas.width = width;
                    canvas.height = height;
                    const ctx = canvas.getContext('2d');
                    ctx.drawImage(img, 0, 0, width, height);
                    canvas.toBlob((blob) => { resolve(blob); }, file.type, options.quality);
                };
                img.onerror = reject;
            };
            reader.onerror = reject;
        });
    }

    // --- Form Submission with Fetch and Resizing ---
    const form = document.getElementById('stationForm');
    const submitButton = document.getElementById('submit-button');
    const formMessage = document.getElementById('form-message');

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        submitButton.disabled = true;
        submitButton.textContent = 'Procesando imágenes...';
        formMessage.textContent = '';

        const formData = new FormData();
        // Append all text/select fields
        for (const pair of new FormData(form).entries()) {
            if (typeof pair[1] === 'string') {
                formData.append(pair[0], pair[1]);
            }
        }

        try {
            // Handle image resizing based on visibility and requirement
            const imageInputs = [
                document.getElementById('imagen_estacion'),
                document.getElementById('evidencia_salida'),
                document.getElementById('evidencia_entrada'),
                document.getElementById('evidencia_resguardo')
            ];

            const resizePromises = imageInputs.map(async (input) => {
                // Only process visible and selected files
                if (input.offsetParent !== null && input.files.length > 0) {
                    const resizedBlob = await resizeImage(input.files[0]);
                    formData.append(input.name, resizedBlob, input.files[0].name);
                }
            });

            await Promise.all(resizePromises);

            submitButton.textContent = 'Enviando...';

            const url = 'https://grammermx.com/Ingenieria/MachineryTracker/dao/daoRegistroMaquinaria.php'; // Your PHP script URL
            const response = await fetch(url, {
                method: 'POST',
                body: formData
            });

            if (!response.ok) {
                throw new Error(`Error del servidor: ${response.statusText}`);
            }

            const result = await response.json();

            if (result.status === 'success') {
                formMessage.innerHTML = `<p class="text-green-600 font-semibold">${result.message}</p>`;
                form.reset();
                // Clear previews and reset form state
                ['preview_imagen_estacion', 'preview_evidencia_salida', 'preview_evidencia_entrada', 'preview_evidencia_resguardo'].forEach(id => {
                    document.getElementById(id).innerHTML = '<span>Vista Previa</span>';
                });
                handleDisposicionChange();
            } else {
                throw new Error(result.message || 'Ocurrió un error desconocido.');
            }

        } catch (error) {
            formMessage.innerHTML = `<p class="text-red-600 font-semibold">Error: ${error.message}</p>`;
        } finally {
            submitButton.disabled = false;
            submitButton.textContent = 'Registrar Estación';
        }
    });

</script>

</body>
</html>
