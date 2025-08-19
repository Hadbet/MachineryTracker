<?php
include_once('db/db_Machinery.php');

try {
    $userId = $_POST['user_id'] ?? null;
    $nombre = $_POST['nombre'] ?? '';
    $nomina = $_POST['nomina'] ?? '';
    $correo = $_POST['correo'] ?? '';
    $rol = $_POST['rol'];
    $estatus = $_POST['estatus'];
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
        $params[] = password_hash($password, PASSWORD_DEFAULT);
        $types .= "s";
    }

    $sql .= " WHERE `IdUsuario` = ?";
    $params[] = $userId;
    $types .= "i";

    $stmt = $conex->prepare($sql);
    $stmt->bind_param($types, ...$params);

    $stmt->execute();

    echo "UPDATE `Usuarios` SET `Nombre` = $nombre, `Correo` = $correo, `rol` = $rol, `estatus` = $estatus  WHERE `IdUsuario` = $userId";

    header('Content-Type: application/json');
    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Usuario actualizado exitosamente."]);
    } else {
        echo json_encode(["success" => true, "message" => "No se realizaron cambios."]);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
