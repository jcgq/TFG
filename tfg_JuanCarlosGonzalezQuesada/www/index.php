<?php
require_once "/usr/local/lib/php/vendor/autoload.php";

$loader = new \Twig\Loader\FilesystemLoader('templates');
$twig = new \Twig\Environment($loader);

$title = 'Inicio';

$URL = explode("/", $_SERVER['QUERY_STRING']);

if(sizeof($URL) > 1){
    if($URL[0] === "about")
        require_once("about_me.php");
    elseif($URL[0] === "login")
        require_once("login/index.php");
    elseif ($URL[0] === "opinar")
        require_once "user/seleccionarProblema.php";
    elseif($URL[0] === "resueltos")
        require_once("resueltos.php");
    elseif ($URL[0] === "crear-problema")
        require_once "admin/precedenteCalcular.php";
    elseif ($URL[0] === "calcular-problema")
        require_once "admin/calcularProblema.php";
    elseif ($URL[0] === "modificar")
        require_once "admin/seleccionarProblemaModificar.php";
    elseif ($URL[0] === "eliminar")
        require_once "admin/borrarProblema.php";
    elseif ($URL[0] === "recalcular")
        require_once "admin/seleccionProblemaRehacer.php";
}
else {
    include "login/misc/pagehead.php";
    require 'login/misc/pullnav.php';

    echo $twig->render('index.html', ['logged_in' => $auth->isLoggedIn(), 'is_admin' => $auth->isAdmin(), 'is_SuperAdmin' => $auth->isSuperAdmin(), 'nombre' => $_SESSION['username']]);

}

?>
