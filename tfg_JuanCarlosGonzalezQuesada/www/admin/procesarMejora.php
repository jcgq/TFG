<?php
    $userrole = 'Superadmin';
    $title = 'Creando un problema nuevo';
    require '../login/misc/pagehead.php';
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);
    include('../funcionesBD.php');
    $idProblema = $_POST['idProblema'];
    $nAlfa=$_POST['alfa'];
    $nbeta=$_POST['beta'];
    $ngamma=$_POST['gama'];
    $nUmbral=$_POST['umbral'];
    $descripcion = $_POST['descripcion'];


    procesandoMejora($idProblema, $nAlfa, $nbeta, $ngamma, $nUmbral, $descripcion);
?>

<script type="text/javascript">
    window.location= '/modificar/';
</script>








