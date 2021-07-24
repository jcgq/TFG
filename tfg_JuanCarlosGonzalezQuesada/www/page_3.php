<?php
$title = "Página pública";
include "login/misc/pagehead.php";
?>
</head>
<body>
  <?php require 'login/misc/pullnav.php'; ?>
    <div class="container">

        <h2>Página pública</h2>
        <?php
            echo shell_exec("python3 ./librerias/holamundo.py 'hoa' 'hola2'");

        ?>



    </div>
</body>
</html>


