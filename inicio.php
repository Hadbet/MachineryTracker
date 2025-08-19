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
    <link href="css/main.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

<!-- Header Navigation -->
<header class="bg-gray-800 text-white shadow-lg">
    <?php include 'estaticos/nav.php'; ?>
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
                    <label for="proyecto" class="block text-sm font-medium text-gray-700 mb-1">Proyecto <span
                                class="text-red-500">*</span></label>
                    <input type="text" id="proyecto" name="proyecto" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Station Name -->
                <div>
                    <label for="nombre_estacion" class="block text-sm font-medium text-gray-700 mb-1">Nombre de la
                        Estación <span class="text-red-500">*</span></label>
                    <input type="text" id="nombre_estacion" name="nombre_estacion" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Responsible Person -->
                <div>
                    <label for="responsable" class="block text-sm font-medium text-gray-700 mb-1">Responsable <span
                                class="text-red-500">*</span></label>
                    <input type="text" id="responsable" name="responsable" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer"
                           value="<?php echo $nombre; ?>">
                </div>
                <!-- Fixed Asset (Optional) -->
                <div>
                    <label for="activo_fijo" class="block text-sm font-medium text-gray-700 mb-1">Activo Fijo
                        (Opcional)</label>
                    <input type="text" id="activo_fijo" name="activo_fijo"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Station Image -->
                <div class="md:col-span-2">
                    <label for="imagen_estacion" class="block text-sm font-medium text-gray-700 mb-1">Imagen de la
                        Estación <span class="text-red-500">*</span></label>
                    <input type="file" id="imagen_estacion" name="imagen_estacion" required accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="preview_imagen_estacion" class="image-preview mt-2"><span>Vista Previa</span></div>
                </div>
                <!-- Disposition -->
                <div class="md:col-span-2">
                    <label for="disposicion" class="block text-sm font-medium text-gray-700 mb-1">Disposición <span
                                class="text-red-500">*</span></label>
                    <select id="disposicion" name="disposicion" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        <option value="en_uso">En uso</option>
                        <option value="resguardo">Resguardo</option>
                        <option value="scrap">Scrap</option>
                        <option value="modificacion">En modificación</option>
                    </select>
                </div>
                <!-- Exit Evidence -->
                <div id="container_evidencia_salida">
                    <label for="evidencia_salida" class="block text-sm font-medium text-gray-700 mb-1">Evidencia de
                        Salida <span class="text-red-500">*</span></label>
                    <input type="file" id="evidencia_salida" name="evidencia_salida" required accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="preview_evidencia_salida" class="image-preview mt-2"><span>Vista Previa</span></div>
                </div>
                <!-- Entry Evidence (Optional) -->
                <div id="container_evidencia_entrada">
                    <label for="evidencia_entrada" class="block text-sm font-medium text-gray-700 mb-1">Evidencia de
                        Entrada (Opcional)</label>
                    <input type="file" id="evidencia_entrada" name="evidencia_entrada" accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="preview_evidencia_entrada" class="image-preview mt-2"><span>Vista Previa</span></div>
                </div>
                <!-- Custody Evidence -->
                <div id="container_evidencia_resguardo">
                    <label for="evidencia_resguardo" class="block text-sm font-medium text-gray-700 mb-1">Evidencia de
                        Resguardo <span class="text-red-500">*</span></label>
                    <input type="file" id="evidencia_resguardo" name="evidencia_resguardo" required accept="image/*"
                           class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    <div id="preview_evidencia_resguardo" class="image-preview mt-2"><span>Vista Previa</span></div>
                </div>
            </div>
        </div>

        <!-- Section 2: External Company Contact -->
        <div>
            <h2 class="text-2xl font-bold text-gray-800 border-b-2 border-blue-600 pb-2 mb-6">Contacto de Empresa
                Externa</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Contact Name -->
                <div>
                    <label for="contacto_nombre" class="block text-sm font-medium text-gray-700 mb-1">Nombre <span
                                class="text-red-500">*</span></label>
                    <input type="text" id="contacto_nombre" name="contacto_nombre" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Company Name -->
                <div>
                    <label for="contacto_empresa" class="block text-sm font-medium text-gray-700 mb-1">Empresa <span
                                class="text-red-500">*</span></label>
                    <select id="contacto_empresa" name="contacto_empresa" required
                            class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                        <option value="GXIN">GXIN</option>
                        <option value="QRO-3330">QRO-3330</option>
                        <option value="TET-3310">TET-3310</option>
                    </select>
                </div>
                <!-- Phone Number -->
                <div>
                    <label for="contacto_telefono" class="block text-sm font-medium text-gray-700 mb-1">Teléfono <span
                                class="text-red-500">*</span></label>
                    <input type="tel" id="contacto_telefono" name="contacto_telefono" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
                <!-- Address -->
                <div class="md:col-span-2">
                    <label for="contacto_direccion" class="block text-sm font-medium text-gray-700 mb-1">Dirección <span
                                class="text-red-500">*</span></label>
                    <textarea id="contacto_direccion" name="contacto_direccion" rows="3" required
                              class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer"></textarea>
                </div>
                <div class="md:col-span-2">
                    <label for="contacto_correo" class="block text-sm font-medium text-gray-700 mb-1">Correo <span
                                class="text-red-500">*</span></label>
                    <input type="text" id="contacto_correo" name="contacto_correo" required
                           class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus-ring-grammer">
                </div>
            </div>
        </div>

        <!-- Submit Button -->
        <div class="mt-8">
            <button type="submit" id="submit-button"
                    class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-lg font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-transform transform hover:scale-105">
                Registrar Estación
            </button>
        </div>

        <!-- Message area for success or error -->
        <div id="form-message" class="mt-4 text-center"></div>
    </form>
</main>
<?php include 'estaticos/piePagina.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="js/inicio.js"></script>

</body>
</html>
