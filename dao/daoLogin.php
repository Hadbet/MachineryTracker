<?php
session_start();
include_once('db/db_Machinery.php');

$nomina = $_POST['nomina'] ?? '';
$password = $_POST['password'] ?? '';

if (empty($nomina) || empty($password)) {
    // Si faltan datos, enviamos una respuesta de error.
    header('Content-Type: application/json');
    echo json_encode(["success" => false, "message" => "Por favor, ingresa la nómina y la contraseña."]);
    exit(); // Detenemos la ejecución del script.
}

try {
    $con = new LocalConector();
    $conex = $con->conectar();

    // Preparamos una consulta segura para obtener el usuario por su nómina.
    $stmt = $conex->prepare("SELECT `IdUsuario`, `Nombre`, `Password`, `rol`, `estatus` FROM `Usuarios` WHERE `Nomina` = ?");
    $stmt->bind_param("s", $nomina);
    $stmt->execute();
    $result = $stmt->get_result();

    // --- 3. VERIFICACIÓN DE CREDENCIALES ---
    if ($result->num_rows === 1) {
        // Si se encontró un usuario con esa nómina...
        $user = $result->fetch_assoc();

        // Verificamos si la contraseña proporcionada coincide con la contraseña encriptada en la BD.
        if (password_verify($password, $user['Password'])) {

            // La contraseña es correcta, ahora verificamos si el usuario está activo.
            if ($user['estatus'] == 1) {
                // ¡Login exitoso!

                // Guardamos información útil del usuario en la sesión.
                $_SESSION['user_id'] = $user['IdUsuario'];
                $_SESSION['user_nombre'] = $user['Nombre'];
                $_SESSION['user_rol'] = $user['rol'];

                header('Content-Type: application/json');
                echo json_encode(["success" => true, "message" => "Login exitoso. Redirigiendo..."]);

            } else {
                // El usuario existe pero está inactivo.
                throw new Exception("Tu cuenta está inactiva. Contacta al administrador.");
            }
        } else {
            // La contraseña es incorrecta.
            throw new Exception("Nómina o contraseña incorrecta.");
        }
    } else {
        // No se encontró ningún usuario con esa nómina.
        throw new Exception("Nómina o contraseña incorrecta.");
    }

    $stmt->close();
    $conex->close();

} catch (Exception $e) {
    // Si ocurre cualquier error (contraseña incorrecta, usuario no encontrado, etc.),
    // lo capturamos y enviamos una respuesta de error genérica.
    header('Content-Type: application/json');
    http_response_code(401); // Unauthorized
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
}

?>