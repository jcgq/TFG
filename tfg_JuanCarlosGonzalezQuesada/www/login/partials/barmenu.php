<?php

$auth = new PHPLogin\AuthorizationHandler;

$barmenu = array();

if(!$auth->isLoggedIn()) {
    // Define the buttons in the menu bar
    $barmenu = array(
        "Inicio" => "/",
        "Sobre mí" => "/about/",
    );

}

if($auth->isAdmin() or $auth->isSuperAdmin()) {
    $barmenu = array(
        "Inicio" => "/",
        "Sobre mí" => "/about/",
        "Crear Problema" => "/crear-problema/",
        "Calcular Problema" => "/calcular-problema/",
        "Problemas Resueltos" => "/resueltos/",
        "Opciones sobre Problemas" => array(
            "Modificar Problema" => "/modificar/", "Borrar Problema" => "/eliminar/",
            "Recalcular Problema" => "/recalcular/")
    );
}
if($auth->isLoggedIn() and (!$auth->isAdmin() or !$auth->isSuperAdmin())){
    $barmenu = array(
        "Inicio" => "/",
        "Opinar" => "/opinar/",
        "Problemas Resueltos" => "/resueltos/",
//        "Otras páginas" => array(
//            "PHP-Login Github" => "https://github.com/therecluse26/PHP-Login",
//            "Sitio Root" => "/"),
    );
}