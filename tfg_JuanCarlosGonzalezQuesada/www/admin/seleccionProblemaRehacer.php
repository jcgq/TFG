<?php
    $title = "Borrar un problema";
    $userrole = "Standard User"; // Allow only logged in users
    include "login/misc/pagehead.php";
    require 'login/misc/pullnav.php';
    include('funcionesBD.php');
    require_once "/usr/local/lib/php/vendor/autoload.php";
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $seleccion = seleccionRehacer();
    echo $twig->render('seleccionProblemaRehacer.html',['seleccion' => $seleccion]);
 ?>
