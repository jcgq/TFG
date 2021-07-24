<?php

    $userrole = "Standard User"; // Allow only logged in users

    include('funcionesBD.php');

    $URL = explode("/",  $_SERVER['QUERY_STRING']);


    if(sizeof($URL) > 2){
        if($URL[1] === "rellenar")
            require_once "user/rellenarOpinion.php";
        elseif($URL[1] === "procesar")
            require_once "user/procesarOpinion.php";
    }
    else {
        $title = "Selección del problema";
        include "login/misc/pagehead.php";
        require 'login/misc/pullnav.php';
        $nombre = $_SESSION["username"];
        $filas = seleccionarProblema($nombre);
        echo $twig->render('seleccionarProblema.html',['filas' => $filas]);
    }


 ?>


