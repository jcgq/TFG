<?php
$mysql = realizarConexion();


function realizarConexion(){
    $conexion = new mysqli('mysql', 'root', 'tiger', 'docker');
    if ($conexion->connect_errno) {
        echo "Fallo al conectar a MySQL: (" . $conexion->connect_errno . ") " . $conexion->connect_error;
    }
    else{
        return $conexion;
    }
}

function aniadirProblema($nombre, $expertos, $criterios, $descripcion, $nombresUsuarios, $numAlternativas, $nomAlternativas, $alfa, $beta, $gamma, $pesosCriterios, $pesosExpertos,$umbral, $nomCriterios){
    global  $mysql;
    $consulta = "INSERT INTO problemas (idProblema, nombre, numExpertos, numCriterios, descripcion, opiniones, usuarios, numAlternativas,terminado, resuelto, solucion, alternativas, alfa, beta, gamma, pesosCriterios, pesosExpertos, qs, consistencias, umbral, urlImagen, criterios, mejorada) VALUES ('0', '$nombre', '$expertos', '$criterios', '$descripcion', '0', '$nombresUsuarios', '$numAlternativas', '0', '0', '0', '$nomAlternativas', '$alfa', '$beta', '$gamma', '$pesosCriterios', '$pesosExpertos', '0', '0', '$umbral', 'Ninguna ruta especificada', '$nomCriterios', '0')";
    $mysql->query($consulta);
}

function obtenerTodosId(){
    global  $mysql;
    $consulta = "SELECT * FROM problemas ORDER BY idProblema DESC";
    $resultado = $mysql->query($consulta);
    $fila = $resultado->fetch_row();
    return $fila;
}

function aniadirUsuariosCrearProblema($idAniadir,$id){
    global  $mysql;
    $consulta = "INSERT INTO asignarProblemaUsuario (idProblema, username, opinionExperto, realizada) VALUES ('$idAniadir','$id', '', 0)";
    $mysql->query($consulta);
}

function obtenerMiembros(){
    global $mysql;
    $resultado=array();
    $query = $mysql -> query ("SELECT * FROM members");
    while ($valores = mysqli_fetch_array($query)) {
        array_push($resultado, $valores['username']);
    }
    return $resultado;
}


function seleccionEliminar(){
    global $mysql;

    $consulta = "SELECT *  FROM problemas WHERE mejorada='0'";
    $resultado=$mysql->query($consulta);

    $salida=array();
    while($fila = $resultado->fetch_row()) {
        array_push($salida, $fila);
    }
    return $salida;
}


function eliminarProblema($id){
    global $mysql;
    $consulta = "SELECT idProblemaMejora FROM asignarMejoraAProblema WHERE idProblemaInicial = '$id'";
    $resultado = $mysql->query($consulta);

    while($fila = $resultado->fetch_row()){
        $consulta = "DELETE FROM problemas WHERE idProblema = '$fila[0]'";
        $mysql->query($consulta);
    }
    $consulta = "DELETE FROM problemas WHERE idProblema = '$id'";
    $mysql->query($consulta);
}


function seleccionRehacer(){
    global $mysql;
    $consulta = "SELECT *  FROM problemas WHERE resuelto='1' ORDER BY nombre";
    $resultado=$mysql->query($consulta);
    $salida=array();
    while($fila = $resultado->fetch_row()) {
        array_push($salida, $fila);
    }
    return $salida;
}

function seleccionModificar($opcion, $nombre){
    global $mysql;
    if($opcion == 0){
        $consulta = "SELECT * FROM problemas WHERE resuelto='1' AND mejorada='0'";
    }
    else{
        $consulta = "SELECT * FROM problemas p INNER JOIN asignarProblemaUsuario ap on p.idProblema = ap.idProblema WHERE ap.realizada='1' and ap.username='$nombre' and p.resuelto='1'";
    }
    $resultado=$mysql->query($consulta);
    $salida=array();

    while($fila = $resultado->fetch_row()) {
        array_push($salida, $fila);
    }
    return $salida;
}

function funcionRehacer($id){
    global $mysql;
    $consulta = "UPDATE problemas SET resuelto='0' WHERE idProblema = '$id'";
    $mysql->query($consulta);
}

function calcularProblema(){
    global $mysql;
    $consulta = "SELECT * FROM problemas WHERE terminado='1' and resuelto='0'";
    $resultado=$mysql->query($consulta);
    $salida=array();
    while($fila = $resultado->fetch_row()) {
        array_push($salida, $fila);
    }
    return $salida;
}


function procesandoMejora($idProblema, $nAlfa, $nbeta, $ngamma, $nUmbral, $descripcion){
    global $mysql;
    $consulta = "SELECT * FROM problemas WHERE idProblema = '$idProblema'";
    $resultado=$mysql->query($consulta);
    $fila = $resultado->fetch_row();


    $consulta = "INSERT INTO problemas (idProblema, nombre, numExpertos, numCriterios, descripcion, opiniones, usuarios, numAlternativas,terminado, resuelto, solucion, alternativas, alfa, beta, gamma, pesosCriterios, pesosExpertos, qs, consistencias, umbral, urlImagen, criterios, mejorada) VALUES ('0', '$fila[1]', '$fila[2]', '$fila[3]', '$descripcion', '$fila[5]', '$fila[6]', '$fila[7]', '$fila[8]', '0', '0', '$fila[11]', '$nAlfa', '$nbeta', '$ngamma', '$fila[15]', '$fila[16]', '0', '0', '$nUmbral', '0', '$fila[21]', '1')";
    $mysql->query($consulta);

    $consulta = "SELECT * FROM problemas ORDER BY idProblema DESC";
    $resultado = $mysql->query($consulta);

    $fila1 = $resultado->fetch_row();
    $idAniadir=$fila1[0];

    $consulta = "SELECT COUNT(*) FROM asignarMejoraAProblema WHERE idProblemaInicial = '$idProblema'";
    $resultado = $mysql->query($consulta);
    $fila2 = $resultado->fetch_row();
    $numMejoras=$fila2[0];
    $numMejoras=$numMejoras+1;



    $consulta = "INSERT INTO asignarMejoraAProblema (idProblemaInicial, idProblemaMejora, numMejora, descripcionMejora) VALUES ('$idProblema','$idAniadir', '$numMejoras', '$descripcion')";
    $mysql->query($consulta);

    $nNombre=($fila[1]." ".strval($numMejoras));

    $consulta = "UPDATE problemas SET nombre = '$nNombre' WHERE idProblema = '$idAniadir'";
    $resultado=$mysql->query($consulta);

}


function seleccionProblemaAdministrador($opcion, $nombre){
    global $mysql;
    $salida=array();
    if($opcion==1){
        $consulta = "SELECT * FROM problemas WHERE resuelto='1' AND mejorada='0'";
    }
    else{
        $consulta = "SELECT * FROM problemas p INNER JOIN asignarProblemaUsuario ap on p.idProblema = ap.idProblema WHERE ap.realizada='1' and ap.username='$nombre' and p.resuelto='1' AND p.mejorada='0'";
    }

    $resultado2=$mysql->query($consulta);
    while($fila = $resultado2->fetch_row()) {
        array_push($salida, $fila);
    }
    return $salida;
}

function paraCalcular($nombre, $auth){
    global $mysql;
    $salida=array();
    if($auth->isAdmin() or $auth->isSuperAdmin()) {
        $consulta = "SELECT * FROM problemas WHERE  terminado='0' or (resuelto='0' and terminado='1')";
        $resultado=$mysql->query($consulta);
        while ($fila = $resultado->fetch_row()) {
            array_push($salida, $fila);
        }
    }
    if($auth->isLoggedIn() and (!$auth->isAdmin() or !$auth->isSuperAdmin())){
        $consulta = "SELECT * FROM asignarProblemaUsuario ap INNER JOIN problemas p on p.idProblema = ap.idProblema WHERE ap.username='$nombre' and ap.realizada='1' and (p.terminado='0' or (p.resuelto='0' and p.terminado='1'))";
        $resultado=$mysql->query($consulta);
        while ($fila = $resultado->fetch_row()) {
            array_push($salida, $fila);
        }
    }
    return $salida;
}



function seleccionarProblema($nombre){
    global $mysql;
    $consulta = "SELECT * FROM asignarProblemaUsuario ap INNER JOIN problemas p WHERE ap.idProblema=p.idProblema AND ap.username='$nombre' and ap.realizada='0'";
    $resultado=$mysql->query($consulta);
    $salida=array();

    while($fila = $resultado->fetch_row()) {
        array_push($salida, $fila);
    }
    return $salida;
}



function modificarProblemaCriterios($idProblema){
    global $mysql;
    $consulta = "SELECT * FROM problemas WHERE idProblema='$idProblema'";
    $resultado2=$mysql->query($consulta);
    return $resultado2->fetch_row();
}

function funcionAuxiliarSoluciones($opcion){
    global $mysql;
    $consulta = "SELECT idProblema FROM problemas WHERE nombre='$opcion' and solucion != '0'";
    $resultado=$mysql->query($consulta);
    return $resultado->fetch_row();
}

function getProblemaConSolucion($auxID){
    global $mysql;
    $consulta = "SELECT * FROM problemas WHERE idProblema='$auxID' and solucion != '0'";
    $resultado=$mysql->query($consulta);
    return $resultado->fetch_row();
}

function obtenerProblemaAsignacion($id){
    global $mysql;
    $consulta = "SELECT * FROM problemas p INNER JOIN asignarMejoraAProblema ap ON p.idProblema = ap.idProblemaInicial AND p.idProblema='$id' and p.solucion != '0'";
    $resultado=$mysql->query($consulta);
    $resultados=array();
    if($resultado->num_rows>0){
        while($row = $resultado->fetch_array()){
            $resultados[] = $row;
        }
    }
    return $resultados;
}
$consulta2 = "SELECT idProblemaMejora FROM asignarMejoraAProblema WHERE idProblemaMejora='$id' ";

function obtenerAsignacion($id){
    global $mysql;
    $consulta = "SELECT idProblemaMejora FROM asignarMejoraAProblema WHERE idProblemaMejora='$id' ";
    $resultado=$mysql->query($consulta);
    return $resultado->fetch_row();
}


function escogerConsulta($idOpcion, $id){
    global $mysql;
    if(empty($idOpcion)){
        $consulta = "SELECT * FROM problemas WHERE idProblema='$id'";
    }
    else{
        $consulta = "SELECT * FROM problemas WHERE idProblema='$idOpcion'";
    }
    $resultado=$mysql->query($consulta);
    return $resultado->fetch_row();
}

function obtenerValoresModificables($id){
    global $mysql;
    $consulta = "SELECT alfa, beta, gamma, umbral, mejorada FROM problemas WHERE idProblema='$id'";
    $resultado=$mysql->query($consulta);
    return $resultado->fetch_row();
}

function obtenerOpiniones($id){
    global $mysql;
    $consulta = "SELECT opinionExperto, username FROM asignarProblemaUsuario WHERE idProblema='$id'";
    $usuariosAux = "SELECT usuarios FROM problemas WHERE idProblema='$id'";
    $usuarios = $mysql->query(($usuariosAux));
    $stringUsuarios = $usuarios->fetch_row();
    $arrayUsuarios = explode(",", substr($stringUsuarios[0], 0, -1));
    $resultado=$mysql->query($consulta);
    $opiniones = "";
    $opAux=array();
    while($fila = $resultado->fetch_row()) {
        array_push($opAux, $fila[1]);
        array_push($opAux, $fila[0]);
    }
    for($i = 0; $i < sizeof($arrayUsuarios); $i++){
        for($j = 0; $j < sizeof($opAux); $j=$j+2){
            if($arrayUsuarios[$i] == $opAux[$j]){
                $opiniones.=$opAux[$j+1].",";
            }
        }
    }

    return $opiniones;
}

function problemaResuelto($opiniones, $id){
    global $mysql;
    $consulta = "UPDATE problemas SET resuelto = '1', opiniones='$opiniones' WHERE idProblema = '$id'";
    $resultado=$mysql->query($consulta);
}

function problemaResueltoSinOpinion($id){
    global $mysql;
    $consulta = "UPDATE problemas SET resuelto = '1' WHERE idProblema = '$id'";
    $resultado=$mysql->query($consulta);
}


function rellenarOpinion($opinion, $id, $nombre){
    global $mysql;
    $consulta = "UPDATE asignarProblemaUsuario SET opinionExperto = '$opinion', realizada='1' WHERE idProblema = '$id' AND username = '$nombre';";
    $mysql->query($consulta);
}


function problemaSePuedeCalcular($id){
    global $mysql;
    $consultaFinalizarPrograma = "SELECT numExpertos FROM problemas WHERE idProblema = '$id' ";
    $consultaCuantosTerminado = "SELECT * FROM asignarProblemaUsuario WHERE idProblema = '$id' AND realizada='1'";
    $resultado1=$mysql->query($consultaFinalizarPrograma);
    $resultado2=$mysql->query($consultaCuantosTerminado);
    $numExpertos = $resultado1->fetch_row();

    $contador=0;
    while($numExpertosRealizado = $resultado2->fetch_row()){
        $contador=$contador+1;
    }
    if($contador==$numExpertos[0]){
        $consulta = "UPDATE problemas SET terminado = '1' WHERE idProblema = '$id'";
        $mysql->query($consulta);
        shell_exec("python3 librerias/correoSePuedeCalcularProblema.py '$id'");
    }
}
























































function cerrarConexion(){
    global $mysql;
    $mysql->close();
    print("Cierro conexion");
}

?>
