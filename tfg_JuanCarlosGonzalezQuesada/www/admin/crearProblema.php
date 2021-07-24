<?php
    if($_POST["expertosP"]){
        $userrole = 'Superadmin';
        $title = 'Creando un problema nuevo';
        require 'login/misc/pagehead.php';
        require 'login/misc/pullnav.php';
        $settingsArr = $conf->pullAllSettings(new PHPLogin\AuthorizationHandler);
        include('funcionesBD.php');
        include('funcionesPHP.php');
        require_once "/usr/local/lib/php/vendor/autoload.php";
        $loader = new \Twig\Loader\FilesystemLoader('templates');
        $twig = new \Twig\Environment($loader);


        foreach ($_POST as $clave){
            if(empty($clave)){
?>
                <script type="text/javascript">
                    window.alert("Creo que estás intentando malmeter al sistema. Empiece de nuevo y corramos un túpido velo")
                    window.location= '/crear-problema/';
                </script>
<?php
            }
        }
        $expertos=$_POST["expertosP"];
        $criterios=$_POST["criteriosP"];
        $alternativas=$_POST["alternativasP"];

        $continuar = validarPredecesor($expertos, $criterios, $alternativas);
        if(!empty($continuar)){
            $miembros = obtenerMiembros();
            echo $twig->render('crearProblema.html', ['numExpertos' => $expertos, 'miembros' => $miembros, 'numCriterios' => $criterios, 'numAlternativas' => $alternativas]);
        }
        else{
?>
            <script type="text/javascript">
                window.alert("Creo que estás intentando malmeter al sistema. Empiece de nuevo y corramos un túpido velo")
                window.location= '/crear-problema/';
            </script>
<?php
        }

    }
    else{
        header("Location: /crear-problema/");
    }
?>
