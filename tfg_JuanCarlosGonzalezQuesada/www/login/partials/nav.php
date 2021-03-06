<nav class="navbar navbar-default">
    <div class="container-fluid">
        <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapsed" aria-expanded="false">
        <span class="glyphicon glyphicon-menu-hamburger"></span>
      </button>

<?php
//SITE LOGO (IF SET) OR SITE NAME
if (str_replace(' ', '', $this->mainlogo) == '') {
    //No logo, just renders site name as link
    echo '<ul class="nav navbar-nav navbar-left"><li class="sitetitle"><a class="navbar-brand" href="'.$this->base_url.'">'.$this->site_name.'</a></li></ul>';
} else {
    //Site main logo as link
    echo '<ul class="nav navbar-nav navbar-left"><li class="mainlogo"><a class="navbar-brand" href="'.$this->base_url.'"><img src="'.$this->mainlogo.'" height="36px"></a></li></ul>';
}
?>
<!-- Collect the nav links, forms, and other content for toggling -->
<div class="collapse navbar-collapse" id="navbar-collapsed">

<!-- BOOTSTRAP NAV LINKS GO HERE. USE <li> items with <a> links inside of <ul> -->

<?php

if (!is_array($barmenu)) {
    // If no menu array is specified as override, try to fallback on menu file
    $menu_file = dirname(__FILE__) . "/barmenu.php";
    if (file_exists($menu_file)) {
        include $menu_file;
    }
}

if (is_array($barmenu)) {
    echo '<ul class="nav navbar-nav">';

    foreach ($barmenu as $btn => $url) {
        if (is_array($url)) {
            echo "<li class=\"dropdown\">";
            echo "<a href=\"#\" class=\"dropdown-toggle\" data-toggle=\"dropdown\" role=\"button\" aria-haspopup=\"true\" aria-expanded=\"false\">";
            echo $btn;
            echo "<span class=\"caret\"></span>";
            echo "</a>";
            echo "<ul class=\"dropdown-menu\">";

            foreach ($url as $sub_btn => $sub_url) {
                echo "<li><a href=\"" . (PHPLogin\MiscFunctions::isAbsUrl($sub_url) ? $sub_url : $this->base_url . '/' . $sub_url) . "\">$sub_btn</a></li>";
            }

            echo "</ul>";
        } else {
            echo "<li><a href=\"" . (PHPLogin\MiscFunctions::isAbsUrl($url) ? $url : $this->base_url . '/' . $url) . "\">$btn</a></li>";
        }
    }

    echo "</ul>";
}

?>

<?php
// SIGN IN / USER SETTINGS BUTTON
$auth = new PHPLogin\AuthorizationHandler;

// Pulls either username or first/last name (if filled out)
if ($auth->isLoggedIn()) {
    $usr = PHPLogin\ProfileData::pullUserFields($_SESSION['uid'], array('firstname', 'lastname'));
    if ((is_array($usr)) && (array_key_exists('firstname', $usr) && array_key_exists('lastname', $usr))) {
        $user = $usr['firstname']. ' ' .$usr['lastname'];
    } else {
        $user = $_SESSION['username'];
    } ?>

    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">
                <?php echo $user; ?>
                <span class="caret"></span>
            </a>
            <ul class="dropdown-menu" aria-labelledby="userDropdown">
                <li><a href="<?php echo $this->base_url; ?>/user/profileedit.php">Editar perfil</a></li>
                <li><a href="<?php echo $this->base_url; ?>/user/accountedit.php">Configuraci??n de la cuenta</a></li>
                <li role="separator" class="divider"></li>

                <!-- Superadmin Controls -->
                <?php if ($auth->isSuperAdmin()): ?>
                  <li><a href="<?php echo $this->base_url; ?>/admin/config.php">Editar la configuraci??n del sitio</a></li>
                  <li><a href="<?php echo $this->base_url; ?>/admin/permissions.php">Gestionar los permisos</a></li>
                  <li role="separator" class="divider"></li>
                <?php endif; ?>
                <!-- Admin Controls -->
                <?php if ($auth->isAdmin()): ?>
                  <li><a href="<?php echo $this->base_url; ?>/admin/users.php">Gestionar usuarios</a></li>
                  <li><a href="<?php echo $this->base_url; ?>/admin/roles.php">Gestionar roles</a></li>
                  <li><a href="<?php echo $this->base_url; ?>/admin/mail.php">Registro de correo</a></li>
                  <li><a href="<?php echo $this->base_url; ?>/login/crearUsuario.php">Registro de usuarios</a></li>
                  <li role="separator" class="divider"></li>
                <?php endif; ?>

                <li><a href="<?php echo $this->base_url; ?>/login/logout.php">Cerrar sesi??n</a></li>
            </ul>
        </li>
    </ul>

<?php
} else {
        //User not logged in?>
    <ul class="nav navbar-nav navbar-right">
    <li class="dropdown"><a href="<?php echo $this->base_url; ?>/login" role="button" aria-haspopup="false" aria-expanded="false">Identificarse
    </a>
    </li>
    </ul>

<?php
    };

?>

</div>
</div>
</div>
</nav>
