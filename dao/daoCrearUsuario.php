<?php
include_once('db/db_Machinery.php');

$password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Encriptamos la contraseña
$nombre = $_POST['nombre'];
$nomina = $_POST['nomina'];
$correo = $_POST['correo'];

try {
    $con = new LocalConector();
    $conex=$con->conectar();

    $stmt = $conex->prepare("INSERT INTO `Usuarios`(`Nombre`, `Nomina`, `Correo`, `Password`, `rol`, `estatus`) VALUES (?,?,?,?,1,1)");
    $stmt->bind_param("ssss", $nombre, $nomina, $correo, $password);

    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        echo json_encode(["success" => true, "message" => "Inserción exitosa"]);
    } else {
        echo json_encode(["success" => false, "message" => "No se pudo insertar el registro"]);
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

?>