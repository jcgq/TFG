<?php
    $userrole = 'Superadmin';
    $title = 'Creando un problema nuevo';
    require '../login/misc/pagehead.php';
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);

    include('../funcionesBD.php');
    include('../funcionesPHP.php');

    $nombre=$_POST['nombreProblema'];
    $usuarios=$_POST['usuarios'];
    $expertos=$_POST['numExpertos'];
    $criterios=$_POST['numCriterios'];
    $numAlternativas=$_POST['numAlternativas'];
    $nombresUsuarios=$_POST['nombresUsuarios'];
    $descripcion=$_POST['descripcion'];
    $nomAlternativas=$_POST['nomAlternativas'];
    $pesosCriterios=$_POST['pesosCriterios'];
    $alfa=$_POST['alfa'];
    $alfa=floatval($alfa);
    $beta=$_POST['beta'];
    $beta=floatval($beta);
    $gamma=$_POST['gamma'];
    $gamma=floatval($gamma);
    $umbral=$_POST['umbral'];
    $nomCriterios=$_POST['nomCriterios'];



    $pesosExpertos="";
    $contador=0;

    for($i=0;$i<$expertos;$i++) {
        for ($j = 0; $j < $criterios; $j++) {
            $pesosExpertos .= $_POST['pesExp_' . $contador] . ",";
            $contador=$contador+1;
        }
    }

    $numCriteriosTotal = $expertos*$criterios;

    $pesosExpertoAux=substr($pesosExpertos, 0, -1);

    $continuar = validarPesos($pesosExpertoAux, $numCriteriosTotal);
    if(!empty($continuar)){
        aniadirProblema($nombre, $expertos, $criterios, $descripcion, $nombresUsuarios, $numAlternativas, $nomAlternativas, $alfa, $beta, $gamma, $pesosCriterios, $pesosExpertos,$umbral, $nomCriterios);
        $id="";

        $fila = obtenerTodosId();
        $idAniadir=$fila[0];


        for ($i=0;$i<strlen($nombresUsuarios); $i++){
            if(strcmp ($nombresUsuarios[$i],",")){
                $id.= $nombresUsuarios[$i];
            }
            else{
                aniadirUsuariosCrearProblema($idAniadir,$id);
                $id="";
            }
        }

        shell_exec("python3 ../librerias/correoCreadoProblema.py '$idAniadir' > /dev/null 2>/dev/null &");
        cerrarConexion();
?>
            <script type="text/javascript">
                window.location= '/calcular-problema/';
            </script>
<?php

    }
    else{
?>
        <script type="text/javascript">
            window.location= '/crearProblema/';
            window.alert("Has sido BIEN JODIDOOOO neado por intentar perjudicar al sistema. Póngase en contacto con el administrador");
        </script>
<?php
    }

?>













