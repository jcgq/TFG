<?php
    $title = "Página pública";
    include "login/misc/pagehead.php";
?>


<?php
    $id = $_POST["seleccionId"];
    $opiniones = obtenerOpiniones($id);
    $opiniones = substr($opiniones, 0, -1);
    $fila = modificarProblemaCriterios($id);
    $idAniadir = 58;
    if($fila[22]==0){
        shell_exec("python3 librerias/funOptimizacion.py '$opiniones' '$fila[2]' '$fila[3]' '$fila[7]' '$fila[12]' '$fila[13]' '$fila[14]' '$fila[15]' '$id' '$fila[16]' '$fila[19]' > /dev/null 2>/dev/null &");
        problemaResuelto($opiniones, $id);
    }
    else{
        shell_exec("python3 librerias/funOptimizacion.py '$fila[5]' '$fila[2]' '$fila[3]' '$fila[7]' '$fila[12]' '$fila[13]' '$fila[14]' '$fila[15]' '$id' '$fila[16]' '$fila[19]' > /dev/null 2>/dev/null &");
        problemaResueltoSinOpinion($id);
    }

?>
<script type="text/javascript">
    window.location= '/calcular-problema/';
</script>











