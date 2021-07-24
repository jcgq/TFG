<?php

    $userrole = 'Superadmin';
    $title = 'Creando un problema nuevo';
    require '../login/misc/pagehead.php';
    $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);
    include('../funcionesBD.php');

    $id=$_POST['seleccionId'];


    funcionRehacer($id);

?>

<script type="text/javascript">
    window.location= '/recalcular/';
</script>






