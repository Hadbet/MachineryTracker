<?php

session_start();
include_once('db/db_Machinery.php');

if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    http_response_code(401); // Código de "No Autorizado"
    echo json_encode(["success" => false, "message" => "No has iniciado sesión."]);
    exit();
}

try {
    $userId = $_SESSION['user_id'];
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['new_password'] ?? '';

    // Validar que se hayan enviado todas las contraseñas.
    if (empty($currentPassword) || empty($newPassword)) {
        throw new Exception("Por favor, completa todos los campos.");
    }

    $con = new LocalConector();
    $conex = $con->conectar();

    // Primero, obtenemos la contraseña actual (encriptada) del usuario desde la BD.
    $stmt = $conex->prepare("SELECT `Password` FROM `Usuarios` WHERE `IdUsuario` = ?");
    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows !== 1) {
        throw new Exception("No se encontró el usuario.");
    }

    $user = $result->fetch_assoc();
    $hashedPasswordFromDB = $user['Password'];

    if (!password_verify($currentPassword, $hashedPasswordFromDB)) {
        throw new Exception("La contraseña actual es incorrecta.");
    }

    $newHashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);

    $updateStmt = $conex->prepare("UPDATE `Usuarios` SET `Password` = ? WHERE `IdUsuario` = ?");
    $updateStmt->bind_param("si", $newHashedPassword, $userId);
    $updateStmt->execute();

    header('Content-Type: application/json');
    if ($updateStmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Contraseña actualizada exitosamente."]);
    } else {
        throw new Exception("No se pudo actualizar la contraseña o es la misma que la anterior.");
    }

    $stmt->close();
    $updateStmt->close();
    $conex->close();

} catch (Exception $e) {
    http_response_code(400); // Bad Request
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}
?>
