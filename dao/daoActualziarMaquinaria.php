<?php
include_once('db/db_Machinery.php');

define('RUTA_ESTACION', 'evidencias/estacion/');
define('RUTA_SALIDAS', 'evidencias/salidas/');
define('RUTA_ENTRADAS', 'evidencias/entradas/');
define('RUTA_RESGUARDO', 'evidencias/resguardo/');

function handleFileUpload($fileData, $targetDirectory) {
    // --- VERIFICACIÓN DE ERRORES MEJORADA ---
    if (!isset($fileData) || !is_uploaded_file($fileData['tmp_name'])) {
        // No se subió un archivo para este campo, lo cual es normal.
        return null;
    }

    if ($fileData['error'] !== UPLOAD_ERR_OK) {
        // Si hay un error, detenemos el proceso y lanzamos una excepción clara.
        throw new Exception("Error al subir el archivo '{$fileData['name']}'. Código de error: {$fileData['error']}");
    }

    $fileTmpPath = $fileData['tmp_name'];
    $fileName = basename($fileData['name']);
    $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    $newFileName = uniqid('', true) . '.' . $fileExtension;
    $dest_path = $targetDirectory . $newFileName;

    if (move_uploaded_file($fileTmpPath, $dest_path)) {
        return $newFileName;
    } else {
        // Si falla al mover, es un problema de permisos.
        throw new Exception("No se pudo mover el archivo a la carpeta de destino. Revisa los permisos.");
    }
}

try {
    $idMaquinaria = $_POST['IdMaquinaria'] ?? null;

    if (empty($idMaquinaria)) {
        throw new Exception("ID del registro no proporcionado.");
    }

    $con = new LocalConector();
    $conex = $con->conectar();

    $fieldsToUpdate = [];
    $params = [];
    $types = "";

    // Campos de texto (sin cambios)
    $textFields = [
        'Proyecto' => $_POST['proyecto'] ?? '', 'NombreEstacion' => $_POST['nombre_estacion'] ?? '',
        'Responsable' => $_POST['responsable'] ?? '', 'ActivoFijo' => $_POST['activo_fijo'] ?? null,
        'Disposicion' => $_POST['disposicion'] ?? '', 'Empresa' => $_POST['contacto_empresa'] ?? '',
        'NombreExterno' => $_POST['contacto_nombre'] ?? '', 'NumeroExterno' => $_POST['contacto_telefono'] ?? '',
        'DireccionExterno' => $_POST['contacto_direccion'] ?? ''
    ];

    foreach ($textFields as $key => $value) {
        $fieldsToUpdate[] = "`$key` = ?";
        $params[] = $value;
        $types .= "s";
    }

    // Campos de imagen (sin cambios en la lógica, pero ahora la función handleFileUpload es más robusta)
    $imageFields = [
        'ImagenEstacion' => ['file' => $_FILES['imagen_estacion'] ?? null, 'path' => RUTA_ESTACION],
        'ImagenSalida' => ['file' => $_FILES['evidencia_salida'] ?? null, 'path' => RUTA_SALIDAS],
        'ImagenEntrada' => ['file' => $_FILES['evidencia_entrada'] ?? null, 'path' => RUTA_ENTRADAS],
        'ImagenEvidenciaResguardo' => ['file' => $_FILES['evidencia_resguardo'] ?? null, 'path' => RUTA_RESGUARDO]
    ];

    foreach ($imageFields as $key => $data) {
        $newFileName = handleFileUpload($data['file'], $data['path']);
        if ($newFileName !== null) {
            $fieldsToUpdate[] = "`$key` = ?";
            $params[] = $newFileName;
            $types .= "s";
        }
    }

    if (empty($fieldsToUpdate)) {
        echo json_encode(["success" => true, "message" => "No se realizaron cambios."]);
        exit();
    }

    $sql = "UPDATE `Maquinaria` SET " . implode(', ', $fieldsToUpdate) . " WHERE `IdMaquinaria` = ?";
    $params[] = $idMaquinaria;
    $types .= "i";

    $stmt = $conex->prepare($sql);
    $stmt->bind_param($types, ...$params);
    $stmt->execute();

    header('Content-Type: application/json');
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Registro actualizado exitosamente."]);
    } else {
        // Ahora, si no hay filas afectadas, también verificamos si hubo un error en la consulta.
        if ($stmt->error) {
            throw new Exception("Error en la base de datos: " . $stmt->error);
        }
        echo json_encode(["success" => true, "message" => "No se detectaron cambios para actualizar."]);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>