<?php

function comprobarOpinion($opinion, $id){
    $fila = modificarProblemaCriterios($id);
    $criterios = intval($fila[3]);
    $alternativas = intval($fila[7]);
    $contador = 1;
    for($i=0; $i<strlen($opinion); $i++){
        if($opinion[$i]==","){
            $contador=$contador+1;
        }
    }
    $numOpiniones=$alternativas*$alternativas-$alternativas;
    $numOpiniones = $numOpiniones*$criterios;
    if($contador == $numOpiniones){
        return True;
    }
    else{
        return False;
    }

}

function validarOpinion($opinion){
    if(preg_match("/[\d]/", $opinion)){
        if(strlen($opinion)==1){
            if($opinion==1 or $opinion==0){
                return True;
            }
            else{
                return False;
            }
        }
        else{
            if(strlen($opinion)==3){
                if(preg_match("/[0-1]\.\[\d]/", $opinion)){
                    return True;
                }
                else{
                    return False;
                }
            }
            else{
                if(strlen($opinion)==4){
                    if(preg_match("/[0-1]\.\[\d][\d]/", $opinion)){
                        return True;
                    }
                    else{
                        return False;
                    }
                }else{
                    return False;
                }
            }
        }
    }
    else{
        return False;
    }
}



function validarPredecesor($expertos, $criterios, $alternativas){
    if(preg_match("/[\d]/", $expertos) and preg_match("/[\d]/", $criterios) and preg_match("/[\d]/", $alternativas)){
        if(intval($expertos)>=2 and intval($criterios)>=2 and intval($alternativas)>=3){
            if(!strstr($expertos, '.') and !strstr($criterios, '.') and !strstr($alternativas, '.')){
                if(!strstr($expertos, ',') and !strstr($criterios, ',') and !strstr($alternativas, ',')){
                    return True;
                }
                else{
                    return False;
                }
            }
            else{
                return False;
            }
        }
        else{
            return False;
        }
    }
    else{
        return False;
    }
}

function validarNombreCantidad($nombreComas, $numero){
    if(sizeof($nombreComas) == $numero){
        return True;
    }
    else{
        return False;
    }
}

function esDecimal($numero){
    $numero=strval($numero);
    if(strlen($numero)==1){
        if($numero==1 or $numero==0){
            return True;
        }
        else{
            return False;
        }
    }
    else{
        if(strlen($numero)==3) {
            if (($numero[0] != 0 and $numero[0] != 1) or $numero[1] != "." or !preg_match("/[0-9]/", $numero[2])) {
                return False;
            } else {
                return True;
            }
        }
        if(strlen($numero)==4){
            if(($numero[0]!=0 and $numero[0]!=1) or $numero[1]!="." or !preg_match("/[0-9]/", $numero[2]) or !preg_match("/[0-9]/", $numero[3])){
                return False;
            }
            else{
                return True;
            }
        }
        else{
            return False;
        }
    }
}

function validarPesos($pesos, $numPesos){
    $pesosAux = explode(",", $pesos);
    $resultado = True;
    if(sizeof($pesosAux)==$numPesos){
        for($i=0; $i<sizeof($pesosAux) and $resultado=True ;$i++){
            $resultado = esDecimal($pesosAux[$i]);
        }
    }
    else{
        $resultado = False;
    }
    return $resultado;
}


function validarMitadProblema($nombre, $descripcion, $nombreAlternativas, $numAlternativas, $nombreCriterios, $numCriterios, $nombreUsuarios, $numUsuarios, $alfa, $beta, $gamma, $umbral, $pesosCriterios, $alternativasString, $criteriosString){
    $correcto=True;
    if(strlen($nombre)>99){
        $correcto=False;
    }
    if(strlen($descripcion)>399){
        $correcto=False;
    }
    if(strlen($alternativasString)>999 or strlen($criteriosString)>999) {
        $correcto=False;
    }
    if(empty(validarNombreCantidad($nombreAlternativas, $numAlternativas))){
        $correcto=False;
    }
    if(empty(validarNombreCantidad($nombreCriterios, $numCriterios))){
        $correcto=False;
    }
    if(empty(validarNombreCantidad($nombreUsuarios, $numUsuarios))){
        $correcto=False;
    }
    if(empty(esDecimal($alfa))){
        $correcto=False;
    }
    if(empty(esDecimal($beta))){
        $correcto=False;
    }
    if(empty(esDecimal($gamma))){
        $correcto=False;
    }
    if(empty(esDecimal($umbral))){
        $correcto=False;
    }
    if(empty(validarPesos($pesosCriterios, $numCriterios))){
        $correcto = False;
    }
    return $correcto;
}


?>
