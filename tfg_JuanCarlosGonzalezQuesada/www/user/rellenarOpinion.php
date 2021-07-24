<?php
    $title = "Página pública";
    include "login/misc/pagehead.php";
    require 'login/misc/pullnav.php';

    $idProblema=$_POST["seleccionId"];
    $userrole = 'Superadmin';
    $fila = modificarProblemaCriterios($idProblema);
    $numCriterios = $fila[3];
    $numAlternativa = $fila[7];
    $nombre = $fila[1];
    $separador = ",";
    $alternativas = $fila[11];
    $separada = explode($separador, $alternativas);
    $pesos=explode($separador, $fila[15]);
    $nombreCriterios=explode(",", $fila[21]);
    echo $twig->render('rellenarOpinion.html',['nombre'=>$nombre, 'id'=>$idProblema, 'alternativas' => $separada, 'pesos'=>$pesos, 'nombreCriterios'=>$nombreCriterios, 'numCriterio' => $numCriterios, 'numAlternativa' =>$numAlternativa]);
 ?>



