<?php
    $userrole = 'Superadmin';
    $title = 'Creando un problema nuevo';
    require 'login/misc/pagehead.php';
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);
    require_once "/usr/local/lib/php/vendor/autoload.php";

    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

    $idProblema = $_POST['seleccionId'];
    $fila = modificarProblemaCriterios($idProblema);

    echo $twig->render('modificarProblema.html',['fila' => $fila]);
?>






