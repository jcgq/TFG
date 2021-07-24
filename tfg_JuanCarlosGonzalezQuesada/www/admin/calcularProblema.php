<?php
    $title = "Selección del problema";
    $userrole = "Standard User"; // Allow only logged in users
    include "login/misc/pagehead.php";
    include('funcionesBD.php');
    require 'login/misc/pullnav.php';

    $URL = explode("/", $_SERVER['QUERY_STRING']);

    if(sizeof($URL) > 2){
        if($URL[1] === "calcular")
            require_once("admin/obtenerTXTopiniones.php");
    }

    $filas = calcularProblema();
    echo $twig->render('calcularProblema.html',['filas' => $filas]);
?>
