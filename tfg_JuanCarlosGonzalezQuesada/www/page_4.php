<?php
$title = "Página del administrador";
$userrole = "Admin"; // Allow only admins to access this page
include "login/misc/pagehead.php";
?>
</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
    <div class="container">

        <h2>Página del administrador</h2>
        <p>Hola, <?=$_SESSION["username"]?>!</p>
        <p>Esta página requiere que un usuario administrador esté conectado</p>
    </div>
</body>
</html>
