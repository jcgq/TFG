<?php

$URL = explode("/",  $_SERVER['QUERY_STRING']);

if(sizeof($URL) > 1){
    if($URL[1] === "forgot")
        require_once "forgotpassword.php";
    elseif ($URL[1] === "signup")
        require_once "signup.php";
}
else{
$userrole = 'loginpage';
$title = 'Identificarse';
include 'misc/pagehead.php';
    echo '<html>';
echo '<head>';
    echo '<link rel="stylesheet" href="css/estilosLogin.css"/>';
echo '</head>';
echo '<body>';
?>
  <?php require 'misc/pullnav.php'; ?>
    <div class="container logindiv">
        <div class="col-sm-4"></div>
        <div class="col-sm-4">
            <form class="text-center" name="loginform" method="post" action="ajax/checklogin.php">
                <h3 class="form-signin-heading"><?php echo $title;?></h3>
                <br>
                <div class="form-group">
                    <input name="myusername" id="myusername" type="text" class="form-control input-lg" placeholder="Nombre de usuario" autofocus>
                    <input name="mypassword" id="mypassword" type="password" class="form-control input-lg" placeholder="Contraseña"> </div>
                <div class="form-group">
                    <button name="Submit" id="submit" class="btn btn-lg btn-primary btn-block" type="submit">Identificarse</button>
                </div>
                <div class="form-group">
                    <div class="checkbox">
                        <input id="remember"  type="checkbox"> Recuérdame</input>
                    </div>
                </div>
            </form>
            <div id="message"></div>
            <p class="text-center"><a href="/login/forgot/">¿Olvidé mi contraseña?</a></p>
            <p class="text-center">o <a href="/login/signup/">Crear una nueva cuenta</a></p>
        </div>
        <div class="col-sm-4"></div>
    </div>
    <!-- The AJAX login script -->
    <script src="js/login.js"></script>
</body>
</html>

<?php
}
?>
