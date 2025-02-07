<?php
    session_start();
?>




<!DOCTYPE html>



<html lang="es">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Login</title>
        <link rel="stylesheet" href="login.css" type="text/css">
    </head>
    
    
    <body>
        
        <?php
            if (isset($_SESSION['autorizado'])) {
                unset($_SESSION['autorizado']);
            }
        ?>
        
        <article>
            <h2>Login</h2>
        
            <form method="POST">
                <input type="text" name="usuario" placeholder="admin" required>
                <input type="password" name="pass" placeholder="admin" required>
                
                <button type="submit" name="comprobar">Ingresar</button>
            </form>
        </article>
        
        <?php
            if (isset($_POST['comprobar'])) {
                if ($_POST['usuario']  == "admin" && $_POST['pass']  == "admin") {
                    
                    $_SESSION['autorizado'] = true;
                    
                    echo "<script type='text/javascript'>window.location.href = 'menu.php';</script>";

                } else {
                    if (isset($_SESSION['alarma'])) {
                        unset($_SESSION['alarma']);
                    }

                    echo "<script type='text/javascript'>alert('Credenciales incorrectas');</script>";
                }
            }

            if (isset($_SESSION['alarma'])) {
                echo "<script type='text/javascript'>alert('" . $_SESSION['alarma'] . "');</script>";
            }
        ?>
    </body>


</html>
