<?php
    require 'login/misc/pagehead.php';
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);
    require_once "/usr/local/lib/php/vendor/autoload.php";
    $loader = new \Twig\Loader\FilesystemLoader('templates');
    $twig = new \Twig\Environment($loader);

        if(isset($_POST['submit'])){
            $opcion = $_POST['seleccionarMejora'];
            $fila1 = funcionAuxiliarSoluciones($opcion);
            $idOpcion=$fila1[0];
            $idPrincipal=$_POST['id'];
        }require 'login/misc/pullnav.php';
    if(!empty($_POST["id"])){
        $_SESSION["idProblema"]=$_POST["id"];
        $id=$_POST["id"];
    }
    else{
        $id=$_SESSION["idProblema"];
    }

    if(empty($id)){
        $id = $_POST["seleccionId"];
        $_SESSION["idProblema"]=$id;
    }

        $fila1 = obtenerProblemaAsignacion($id);
        $fila2 = obtenerAsignacion($id);

        if(!empty($fila1) or !empty($fila2)){
            $auxID=$fila1[0][24];
            $filaAlternativas = getProblemaConSolucion($auxID);
            $nombreInicial=$fila1[0][1].",";

            if(!empty($filaAlternativas)){
                $nombreInicial.=$filaAlternativas[1].",";
            };
            for($i=1; $i<sizeof($fila1);$i++){
                $auxID=$fila1[$i][24];
                $filaAlternativas = getProblemaConSolucion($auxID);
                if(!empty($filaAlternativas)){
                    $nombreInicial.=$filaAlternativas[1].",";
                }
            }
            $nombreInicial=substr($nombreInicial, 0, -1);
            $nombres=explode(",", $nombreInicial);
            $paraAction = htmlspecialchars($_SERVER['PHP_SELF']);

        }

        $fila = escogerConsulta($idOpcion, $id);

        $separador=",";

        $separada = explode($separador, $fila[11]);
        $cadena = substr($fila[10], 1, -2);
        $separada2 = explode($separador, $cadena);

        array_multisort($separada2, SORT_DESC, $separada);

        $cadena = substr($fila[17], 0, -1);
        $qs1 = explode($separador, $cadena);

        for($i=0;$i<$fila[7];$i++){
            $separada2[$i]=substr($separada2[$i],0,5);
        }

        $cadena = substr($fila[18], 0, -1);
        $qs2 = explode($separador, $cadena);
        $qs2[sizeof($qs2)-1]=substr($qs2[sizeof($qs2)-1], 0, -10);

        $imagenFila=escogerConsulta($idOpcion, $id);
        $filaFlechas = obtenerValoresModificables($id);

    echo $twig->render('obtenerSoluciones.html',['filaFlechas' => $filaFlechas, 'imagenFila' => $imagenFila,  'qs1' => $qs1, 'qs2' => $qs2, 'fila' => $fila, 'separada' => $separada, 'separada2' => $separada2, 'id' => $id, 'fila1' => $fila1, 'fila2' => $fila2, 'nombres' => $nombres]);
?>








