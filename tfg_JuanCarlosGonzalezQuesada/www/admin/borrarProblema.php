<?php
    $title = "Borrar un problema";
    $userrole = "Standard User"; // Allow only logged in users
    include "login/misc/pagehead.php";
    require 'login/misc/pullnav.php';
    include('funcionesBD.php');
    require_once "/usr/local/lib/php/vendor/autoload.php";
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);



    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $filas = seleccionEliminar();
    echo $twig->render('borrarProblema.html',['filas' => $filas]);
?>

