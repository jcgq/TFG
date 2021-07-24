<?php

    $userrole = 'Superadmin';
    $title = 'Creando un problema nuevo';
    require 'login/misc/pagehead.php';
    include('funcionesPHP.php');
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);
    require_once "/usr/local/lib/php/vendor/autoload.php";
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $nombre=$_POST['nombre'];
    $usuarios=$_POST['usuarios'];
    $expertos=intval(sizeof($usuarios));
    $criterios=$_POST['criterios'];
    $numAlternativas=$_POST['alternativas'];
    $alfa=$_POST['alfa'];
    $alfa=floatval($alfa);
    $beta=$_POST['beta'];
    $beta=floatval($beta);
    $gamma=$_POST['gama'];
    $gamma=floatval($gamma);


    $pesosCriterios="";
    for($i=0;$i<$criterios;$i++){
        $pesosCriterios.=$_POST['pesCri_'.$i].",";
    }
    $pesosCriterios=substr($pesosCriterios,0,-1);
    $nombreCriterios="";
    $criteriosNombresAux=array();
    $aux="";
    for($i=0;$i<$criterios;$i++){
        $aux = str_replace(',', '.', $_POST['nomCri_'.$i]);
        $nombreCriterios.=$aux.",";
        array_push($criteriosNombresAux, $aux);
    }
    $nombreCriterios=substr($nombreCriterios,0,-1);




    $numAlternativas=$_POST['alternativas'];

    $nombreAlternativas="";
    $alternativasNombresAux=array();
    for($i=0;$i<$numAlternativas;$i++){
        $aux=str_replace(",", ".", $_POST['nomAlter_'.$i]);
        $nombreAlternativas.=$aux.",";
        array_push($alternativasNombresAux, $aux);
    }
    $nombreAlternativas=substr($nombreAlternativas,0,-1);

    $descripcion=$_POST['descripcion'];

    $expertos=intval($expertos);
    $criterios=intval($criterios);

    $usuariosNombresAux=array();
    foreach($_POST['usuarios'] as $seleccion) {
        $usuariosFinal .= $seleccion.",";
        array_push($usuariosNombresAux, $seleccion);
    }
    require 'login/misc/pullnav.php';

    $alternativas=$_POST["alternativas"];

    $umbral=$_POST["umbral"];

    $usuariosAuxiliar = substr($usuariosFinal, 0, -1);
    $continuar = validarMitadProblema($nombre, $descripcion, $alternativasNombresAux, $numAlternativas, $criteriosNombresAux, $criterios,$usuariosNombresAux, $expertos, $alfa, $beta, $gamma, $umbral, $pesosCriterios, $nombreAlternativas, $nombreCriterios);

    if(!empty($continuar)){
        echo $twig->render('procesarCriterio.html',['nombreCriterios'=>$nombreCriterios, 'pesosCriterios'=>$pesosCriterios,'nombreAlternativas'=>$nombreAlternativas, 'alternativas'=>$alternativas, 'usuariosFinal' => $usuariosFinal, 'criterios' =>$criterios, 'descripcion'=>$descripcion, 'nombre' => $nombre, 'expertos' => $expertos, 'alfa' => $alfa, 'beta' => $beta, 'gamma' => $gamma, 'numCriterios' => $criterios, 'numExpertos' => $expertos, 'usuariosNombresAux' => $usuariosNombresAux, 'numAlternativas' => $numAlternativas, 'umbral' => $umbral]);
    }else{
?>
    <script type="text/javascript">
     window.location= '/crearProblema/';
     window.alert("Has sido banneado por intentar perjudicar al sistema. Póngase en contacto con el administrador");
    </script>
<?php
    }


?>









