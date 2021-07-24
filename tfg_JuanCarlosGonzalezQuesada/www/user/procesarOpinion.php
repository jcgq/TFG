<?php
    $title = "Selección del problema";
    $userrole = "Standard User"; // Allow only logged in users
    include "login/misc/pagehead.php";
    require 'login/misc/pullnav.php';
    include('funcionesPHP.php');
    $userrole = 'Superadmin';
    $title = 'Creando un problema nuevo';

    $opinion="";
    $tamanio = sizeof($_POST);
    $formatoCorrecto=True;

    for($i=0; $i<$tamanio-1 and !empty($formatoCorrecto); $i++){
        $post = each($_POST);
        $aux=str_replace(",", ".", $post[1]);
        $opinion .= $aux.",";
        $formatoCorrecto = esDecimal($aux);
    }
    $opinion=substr($opinion,0,-1);

    $id=$_POST["idProblema"];

    if(comprobarOpinion($opinion, $id)==True and $formatoCorrecto==True){
        $nombre = $_SESSION["username"];
        rellenarOpinion($opinion, $id, $nombre);
        problemaSePuedeCalcular($id);
        ?>
    <script type="text/javascript">
        window.location= '/opinar/';
        window.alert("Opinión Enviada!");
    </script>
    <?php
    }
    else{
        if(empty(comprobarOpinion($opinion, $id)) or empty($formatoCorrecto)){
    ?>
            <script type="text/javascript">
                window.location= '/opinar/';
                window.alert("Has sido banneado por intentar perjudicar al sistema. Póngase en contacto con el administrador");
            </script>
    <?php
        }
    }
?>
















