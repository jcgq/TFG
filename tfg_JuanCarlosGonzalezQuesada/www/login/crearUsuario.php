<?php
#$userrole = 'loginpage';
$title = 'Registrarse';
require 'misc/pagehead.php';
?>


<html>
<head>
    <link rel="stylesheet" type="text/css" href="css/estilosLogin.css"/>

<script src="js/signup.js"></script>
<script src="js/jquery.validate.min.js"></script>
<script src="js/additional-methods.min.js"></script>

</head>
<body>

<?php require 'misc/pullnav.php'; ?>

<div class="container logindiv">
    <div class="col-sm-4"></div>
    <div class="col-sm-4">
        <form class="text-center" id="usersignup" name="usersignup" method="post" action="ajax/createuser.php">
            <h3 class="form-signup-heading"><?php echo $title;?></h3>
            <br>
            <input name="newuser" id="newuser" type="text" class="form-control input-lg" placeholder="Username" autofocus>
            <div class="form-group">
                <input name="email" id="email" type="text" class="form-control input-lg" placeholder="Email"> </div>
            <div class="form-group">
                <input name="password1" id="password1" type="password" class="form-control input-lg" placeholder="Password">
                <input name="password2" id="password2" type="password" class="form-control input-lg" placeholder="Repeat Password"> </div>
            <div class="form-group">
                <button name="Submit" id="submitbtn" class="btn btn-lg btn-primary btn-block" type="submit">Registrarse</button>
            </div>
        </form>
        <div id="message"></div>
        <p id="orlogin" class="text-center">o <a href="/">Identificarse</a></p>
    </div>
    <div class="col-sm-4"></div>
</div>
<?php $conf = new PHPLogin\AppConfig; ?>
<script>
    $("#usersignup").validate({
        rules: {
            email: {
                email: true
                , required: true
            }
            , password1: {
                required: true
                <?php if ($conf->password_policy_enforce == true) {
                    echo ", minlength: ". $conf->password_min_length;
                }; ?>
            }
            , password2: {
                equalTo: "#password1"
            }
        }
    });
</script>
</body>
</html>
