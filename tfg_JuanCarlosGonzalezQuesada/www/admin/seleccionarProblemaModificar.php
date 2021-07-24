<?php
    require 'login/misc/pagehead.php';
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);
    include('funcionesBD.php');

    require_once "/usr/local/lib/php/vendor/autoload.php";
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    require 'login/misc/pullnav.php';

    $URL = explode("/", $_SERVER['QUERY_STRING']);

    if(sizeof($URL) > 2){
        if($URL[1] === "1")
            require_once("admin/modificarProblema.php");

    }
    else{
        $auth = new PHPLogin\AuthorizationHandler;

        $id = $_POST["seleccionId"];
        $nombre=$_SESSION["username"];
        $opcion = 0;
        if($auth->isAdmin() or $auth->isSuperAdmin()) {
            $seleccion = seleccionModificar($opcion, $nombre);
            echo $twig->render('seleccionarProblemaModificar.html',['seleccion' => $seleccion]);
        }
        if($auth->isLoggedIn() and (!$auth->isAdmin() or !$auth->isSuperAdmin())){
            $opcion=1;
            $seleccion = seleccionModificar($opcion, $nombre);
            echo $twig->render('seleccionarProblemaModificar.html',['seleccion' => $seleccion]);

        }

    }


?>
