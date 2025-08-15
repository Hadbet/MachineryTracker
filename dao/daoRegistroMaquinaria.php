<?php
// guardar_estacion.php

// Incluimos tu archivo de conexión a la base de datos.
include_once('db/db_Machinery.php');

// --- 1. DEFINICIÓN DE RUTAS PARA GUARDAR IMÁGENES ---
// Asegúrate de que estas carpetas existan en tu servidor y tengan permisos de escritura.
define('RUTA_ESTACION', 'dao/evidencias/estacion/');
define('RUTA_SALIDAS', 'dao/evidencias/salidas/');
define('RUTA_ENTRADAS', 'dao/evidencias/entradas/');
define('RUTA_RESGUARDO', 'dao/evidencias/resguardo/');

/**
 * Función de ayuda para manejar la subida de archivos de imagen.
 * Genera un nombre único y mueve el archivo a su destino final.
 *
 * @param array $fileData Datos del archivo provenientes de $_FILES.
 * @param string $targetDirectory La carpeta de destino para guardar la imagen.
 * @return string|null El nombre del archivo guardado o null si no hay archivo o hay un error.
 */
function handleFileUpload($fileData, $targetDirectory) {
    // Verificar si el archivo fue subido y no hay errores.
    if (isset($fileData) && $fileData['error'] === UPLOAD_ERR_OK) {
        $fileTmpPath = $fileData['tmp_name'];
        $fileName = $fileData['name'];
        $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));

        // Generar un nombre de archivo único para evitar sobreescrituras.
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
    // Recuperar los datos de texto del formulario enviados por POST.
    $proyecto = $_POST['proyecto'] ?? '';
    $nombreEstacion = $_POST['nombre_estacion'] ?? '';
    $responsable = $_POST['responsable'] ?? '';
    $activoFijo = $_POST['activo_fijo'] ?? null; // Puede ser nulo
    $disposicion = $_POST['disposicion'] ?? '';
    $empresa = $_POST['contacto_empresa'] ?? '';
    $nombreExterno = $_POST['contacto_nombre'] ?? '';
    $numeroExterno = $_POST['contacto_telefono'] ?? '';
    $direccionExterno = $_POST['contacto_direccion'] ?? '';
    // El correo no viene del formulario, lo dejamos como null.
    $correoExterno = null;

    // Procesar cada una de las imágenes. La función devolverá el nombre del archivo o null.
    $imagenEstacionNombre = handleFileUpload($_FILES['imagen_estacion'] ?? null, RUTA_ESTACION);
    $imagenSalidaNombre = handleFileUpload($_FILES['evidencia_salida'] ?? null, RUTA_SALIDAS);
    $imagenEntradaNombre = handleFileUpload($_FILES['evidencia_entrada'] ?? null, RUTA_ENTRADAS);
    $imagenResguardoNombre = handleFileUpload($_FILES['evidencia_resguardo'] ?? null, RUTA_RESGUARDO);

    // --- 3. INSERCIÓN EN LA BASE DE DATOS ---
    $con = new LocalConector();
    $conex = $con->conectar();

    $Object = new DateTime();
    $Object->setTimezone(new DateTimeZone('America/Denver'));
    $DateAndTime = $Object->format("Y/m/d h:i:s");

    // Usamos sentencias preparadas para máxima seguridad contra inyección SQL.
    $stmt = $conex->prepare(
        "INSERT INTO `Maquinaria` (
            `Proyecto`, `NombreEstacion`, `Responsable`, `ActivoFijo`, `ImagenEstacion`, 
            `Disposicion`, `Empresa`, `NombreExterno`, `NumeroExterno`, `DireccionExterno`, 
            `CorreoExterno`, `ImagenSalida`, `ImagenEntrada`, `ImagenEvidenciaResguardo`, `Fecha`
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,?)"
    );

    // Vinculamos los parámetros a la consulta. 's' significa string.
    $stmt->bind_param("sssssssssssssss",
        $proyecto,
        $nombreEstacion,
        $responsable,
        $activoFijo,
        $imagenEstacionNombre,
        $disposicion,
        $empresa,
        $nombreExterno,
        $numeroExterno,
        $direccionExterno,
        $correoExterno,
        $imagenSalidaNombre,
        $imagenEntradaNombre,
        $imagenResguardoNombre,
        $DateAndTime
    );

    // Ejecutamos la inserción.
    $stmt->execute();

    // --- 4. RESPUESTA AL CLIENTE ---
    header('Content-Type: application/json');
    if ($stmt->affected_rows > 0) {
        echo json_encode(["status" => "success", "message" => "Registro guardado exitosamente."]);
    } else {
        echo json_encode(["status" => "error", "message" => "No se pudo guardar el registro. Error: " . $stmt->error]);
    }

    // Cerramos la sentencia y la conexión.
    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    // Si ocurre un error inesperado, lo capturamos y enviamos una respuesta de error.
    http_response_code(500); // Código de error del servidor
    header('Content-Type: application/json');
    echo json_encode(["status" => "error", "message" => "Error en el servidor: " . $e->getMessage()]);
}
?>
