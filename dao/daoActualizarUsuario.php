<?php
include_once('db/db_Machinery.php');

try {
    $userId = $_POST['user_id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $nomina = $_POST['nomina'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $rol = $_POST['rol'] ?? '2';
    $estatus = $_POST['estatus'] ?? '1';
    $password = $_POST['password'] ?? '';

    if (empty($userId)) {
        throw new Exception("No se proporcionó el ID del usuario a actualizar.");
    }

    $con = new LocalConector();
    $conex = $con->conectar();

    $params = [];
    $types = "";

    $sql = "UPDATE `Usuarios` SET `Nombre` = ?, `Correo` = ?, `rol` = ?, `estatus` = ?";
    $params[] = $nombre;
    $params[] = $correo;
    $params[] = $rol;
    $params[] = $estatus;
    $types .= "ssss";

    if (!empty($password)) {
        $sql .= ", `Password` = ?";
        // Encriptamos la nueva contraseña antes de guardarla.
        $params[] = password_hash($password, PASSWORD_DEFAULT);
        $types .= "s";
    }

    // Completamos la consulta con la cláusula WHERE.
    $sql .= " WHERE `IdUsuario` = ?";
    $params[] = $userId;
    $types .= "i"; // 'i' para integer (ID de usuario)

    // --- 3. EJECUCIÓN DE LA CONSULTA SEGURA ---
    $stmt = $conex->prepare($sql);

    // Usamos el operador "splat" (...) para pasar el array de parámetros a bind_param.
    $stmt->bind_param($types, ...$params);

    $stmt->execute();

    // --- 4. RESPUESTA AL CLIENTE ---
    header('Content-Type: application/json');
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Usuario actualizado exitosamente."]);
    } else {
        // Si affected_rows es 0, puede ser que no hubo error, pero no se cambió ningún dato.
        echo json_encode(["success" => true, "message" => "No se realizaron cambios."]);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    // Si ocurre un error, lo capturamos y enviamos una respuesta clara.
    http_response_code(500); // Error interno del servidor
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
