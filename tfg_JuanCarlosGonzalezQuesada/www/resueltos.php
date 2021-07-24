<?php
    $title = 'Problemas Resueltos';
    require 'login/misc/pagehead.php';
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);
    include('funcionesBD.php');
    require_once "/usr/local/lib/php/vendor/autoload.php";
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $URL = explode("/",  $_SERVER['QUERY_STRING']);

    if(sizeof($URL) > 2){
        if($URL[1] === "visualizar")
            require_once "admin/obtenersoluciones.php";
    }
    else{
        require 'login/misc/pullnav.php';

        $auth = new PHPLogin\AuthorizationHandler;
        $nombre=$_SESSION["username"];
        $id = $_POST["seleccionId"];
        $opcion=0;
        if($auth->isAdmin() or $auth->isSuperAdmin()) {
            $opcion=1;
            $seleccion = seleccionProblemaAdministrador($opcion, $nombre);
            $_SESSION["idProblema"]=NULL;
        }
        if($auth->isLoggedIn() and (!$auth->isAdmin() or !$auth->isSuperAdmin())){
            $seleccion = seleccionProblemaAdministrador($opcion, $nombre);
            $_SESSION["idProblema"]=NULL;
        }
        $nombre=$_SESSION["username"];
        $calcular = paraCalcular($nombre, $auth);
        echo $twig->render('resueltos.html',['seleccion' => $seleccion, 'calcular' => $calcular, 'opcion'=>$opcion]);
    }

?>
