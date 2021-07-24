<?php
$title = "Página de usuario estándar";
include "login/misc/pagehead.php";
?>
<html>
<head>
    <?php require 'login/misc/pullnav.php'; ?>
    <link rel="stylesheet" type="text/css" href="/estilosWWW.css"/>
</head>

<body>

    <div class="divAbout">
        <h2>Página de usuario estándar</h2>
        <?php
            if(!empty($_SESSION["username"])){
                echo "<p> Hola, $_SESSION[username]! </p>";
            }
            else{
                echo "<p> Hola, desconocido! </p>";
            }

        ?>

        <p>Esta página requiere que un usuario estándar esté conectado</p>
    </div>
</body>
</html>
