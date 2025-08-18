<?php
// guardar_estacion.php

// Incluimos tu archivo de conexión a la base de datos.
include_once('db/db_Machinery.php');

// --- 1. DEFINICIÓN DE RUTAS PARA GUARDAR IMÁGENES ---
define('RUTA_ESTACION', 'evidencias/estacion/');
define('RUTA_SALIDAS', 'evidencias/salidas/');
define('RUTA_ENTRADAS', 'evidencias/entradas/');
define('RUTA_RESGUARDO', 'evidencias/resguardo/');

/**
 * Función de ayuda para manejar la subida de archivos de imagen.
 *
 * @param array $fileData Datos del archivo provenientes de $_FILES.
 * @param string $targetDirectory La carpeta de destino para guardar la imagen.
 * @return string|null El nombre del archivo guardado o null si no hay archivo o hay un error.
 */
function handleFileUpload($fileData, $targetDirectory) {
    // Verificar si el archivo fue subido y no hay errores.
    if (isset($fileData) && $fileData['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $fileData['tmp_name'];
        $fileName = basename($fileData['name']); // basename() para seguridad
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $newFileName = uniqid('', true) . '.' . $fileExtension;
        $dest_path = $targetDirectory . $newFileName;

        // Mover el archivo de la carpeta temporal a la carpeta de destino.
        if (move_uploaded_file($fileTmpPath, $dest_path)) {
            return $newFileName; // Devolver solo el nombre del archivo.
        }
    }
    return null; // Devolver null si no se subió archivo o hubo un error.
}

// --- 2. PROCESAMIENTO DE LA SOLICITUD ---
try {
    // Recuperar los datos de texto del formulario.
    $proyecto = $_POST['proyecto'] ?? '';
    $nombreEstacion = $_POST['nombre_estacion'] ?? '';
    $responsable = $_POST['responsable'] ?? '';
    $activoFijo = $_POST['activo_fijo'] ?? null;
    $disposicion = $_POST['disposicion'] ?? '';
    $empresa = $_POST['contacto_empresa'] ?? '';
    $nombreExterno = $_POST['contacto_nombre'] ?? '';
    $numeroExterno = $_POST['contacto_telefono'] ?? '';
    $direccionExterno = $_POST['contacto_direccion'] ?? '';
    $correoExterno = $_POST['contacto_correo'] ?? '';

    // --- VERIFICACIÓN DE ARCHIVOS OBLIGATORIOS ---
    // Esta es la validación clave para evitar el error 'cannot be null'.
    if (!isset($_FILES['imagen_estacion']) || $_FILES['imagen_estacion']['error'] !== UPLOAD_ERR_OK) {
        throw new Exception("El archivo 'Imagen de la Estación' es obligatorio y no se recibió correctamente. Revisa los límites de subida en tu servidor.");
    }
    // Puedes añadir más validaciones aquí para otros campos si es necesario.

    // Procesar las imágenes.
    $imagenEstacionNombre = handleFileUpload($_FILES['imagen_estacion'], RUTA_ESTACION);
    $imagenSalidaNombre = handleFileUpload($_FILES['evidencia_salida'] ?? null, RUTA_SALIDAS);
    $imagenEntradaNombre = handleFileUpload($_FILES['evidencia_entrada'] ?? null, RUTA_ENTRADAS);
    $imagenResguardoNombre = handleFileUpload($_FILES['evidencia_resguardo'] ?? null, RUTA_RESGUARDO);

    // --- 3. INSERCIÓN EN LA BASE DE DATOS ---
    $con = new LocalConector();
    $conex = $con->conectar();

    $Object = new DateTime();
    $Object->setTimezone(new DateTimeZone('America/Denver')); // Considera usar 'America/Mexico_City' si aplica
    $DateAndTime = $Object->format("Y-m-d H:i:s");

    $stmt = $conex->prepare(
        "INSERT INTO `Maquinaria` (
            `Proyecto`, `NombreEstacion`, `Responsable`, `ActivoFijo`, `ImagenEstacion`, 
            `Disposicion`, `Empresa`, `NombreExterno`, `NumeroExterno`, `DireccionExterno`, 
            `CorreoExterno`, `ImagenSalida`, `ImagenEntrada`, `ImagenEvidenciaResguardo`, `Fecha`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"
    );

    $stmt->bind_param("sssssssssssssss",
        $proyecto, $nombreEstacion, $responsable, $activoFijo, $imagenEstacionNombre,
        $disposicion, $empresa, $nombreExterno, $numeroExterno, $direccionExterno,
        $correoExterno, $imagenSalidaNombre, $imagenEntradaNombre, $imagenResguardoNombre, $DateAndTime
    );

    $stmt->execute();

    // --- 4. RESPUESTA AL CLIENTE ---
    header('Content-Type: application/json');
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Registro guardado exitosamente."]);
    } else {
        // Si no se insertaron filas, es probable que haya un error en la base de datos.
        throw new Exception("No se pudo guardar el registro en la base de datos. Error: " . $stmt->error);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    // Si ocurre cualquier error, lo capturamos y enviamos una respuesta clara.
    http_response_code(400); // Bad Request
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => $e->getMessage()]);
}
?>
