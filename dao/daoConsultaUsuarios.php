<?php
include_once('db/db_Machinery.php');

function consultaUsuarios() {
    $usuarios = [];

    try {
        $con = new LocalConector();
        $conex = $con->conectar();

        $sql = "SELECT `IdUsuario`, `Nombre`, `Nomina`, `Correo`, `rol`, `estatus` FROM `Usuarios`";
        $resultado = mysqli_query($conex, $sql);

        if ($resultado) {
            $usuarios = mysqli_fetch_all($resultado, MYSQLI_ASSOC);
        } else {
            error_log("Error en la consulta SQL de usuarios: " . mysqli_error($conex));
        }
        mysqli_close($conex);

    } catch (Exception $e) {
        http_response_code(500); // Error interno del servidor
        $usuarios = ['error' => 'Ocurrió un error al conectar o consultar la base de datos.'];
        error_log("Excepción al consultar usuarios: " . $e->getMessage());
    }

    header('Content-Type: application/json');
    header('Cache-Control: no-cache, must-revalidate');
    echo json_encode(array("data" => $usuarios));
}

consultaUsuarios();

?>
