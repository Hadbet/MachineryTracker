<?php
include_once('db/db_Machinery.php');
consultaMaquinaria();
function consultaMaquinaria(){
    $con = new LocalConector();
    $conex=$con->conectar();

    $datos = mysqli_query($conex, "SELECT `IdMaquinaria`, `Proyecto`, `NombreEstacion`, `Responsable`, `ActivoFijo`, `ImagenEstacion`, `Disposicion`, `Empresa`, `NombreExterno`, `NumeroExterno`, `DireccionExterno`, `CorreoExterno`, `Fecha`, `ImagenSalida`, `ImagenEntrada`, `ImagenEvidenciaResguardo` FROM `Maquinaria`");
    
    $resultado = mysqli_fetch_all($datos, MYSQLI_ASSOC);
    echo json_encode(array("data"=>$resultado));
}
?>