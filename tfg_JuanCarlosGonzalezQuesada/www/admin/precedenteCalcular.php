<?php
    $userrole = 'Superadmin';
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $URL = explode("/", $_SERVER['QUERY_STRING']);

    if(sizeof($URL) > 2){
        if($URL[1] === "1")
            require_once("admin/crearProblema.php");
        elseif($URL[1] === "2")
            require_once("admin/procesarCriterios.php");
    }
    else{
        $title = 'Creando un problema nuevo';
        require 'login/misc/pagehead.php';
        require 'login/misc/pullnav.php';
        $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);


        echo $twig->render('precedenteCalcular.html');

    }
?>
