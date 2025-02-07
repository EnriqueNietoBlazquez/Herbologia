<?php
    session_start();
    
    if ( isset($_SESSION['autorizado']) && isset($_POST['listar']) ) {
        /*
            No tiene sentido importar la libreria y crear la conexión si el acceso es ilegal, 
            por ello he esperado a que la condicion se cumpliera para hacer esas cosas,
            de hecho así es más seguro
        */
        require 'basesDeDatos.lib.php';
        $conector = mysqli_connect ("localhost", "root", "", "enriqueNieto");
?>




<!DOCTYPE html>



<html lang="es">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Listar</title>
        <link rel="stylesheet" href="menuYFondo.css" type="text/css">
        <link rel="stylesheet" href="tablas.css" type="text/css">
    </head>


    <body>

        <header>
            <h1>Listar</h1>

            <nav>
                <ul id="menu">
                
                    <li class="categoria"><a>Insertar</a>
                        <ul class="submenu">
                            <li>
                                <form action="insertar.php" method="POST">
                                    <input type="hidden" name="propiedades">
                                    <button type="submit" name='insertar'>Insertar Propiedades</button>
                                </form>
                            </li>
                            
                            <li>
                                <form action="insertar.php" method="POST">
                                    <input type="hidden" name="plantas">
                                    <button type="submit" name='insertar'>Insertar Planta</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="categoria"><a>Modificar</a>
                        <ul class="submenu">
                            <li>
                                <form action="desplegablesModificar.php" method="POST">
                                    <input type="hidden" name="propiedades">
                                    <button type="submit" name='modificar'>Modificar Propiedades</button>
                                </form>
                            </li>
                            
                            <li>
                                <form action="desplegablesModificar.php" method="POST">
                                    <input type="hidden" name="plantas">
                                    <button type="submit" name='modificar'>Modificar Planta</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="categoria"><a>Listar</a>
                        <ul class="submenu">
                            <li>
                                <form action="listar.php" method="POST">
                                    <input type="hidden" name="propiedades">
                                    <button type="submit" name='listar'>Listar Propiedades</button>
                                </form>
                            </li>
                            
                            <li>
                                <form action="listar.php" method="POST">
                                    <input type="hidden" name="plantas">
                                    <button type="submit" name='listar'>Listar Plantas</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="categoria"><a>Buscar</a>
                        <ul class="submenu">
                            <li>
                                <form action="buscar.php" method="POST">
                                    <input type="hidden" name="propiedades">
                                    <button type="submit" name='quieroBuscar'>Buscar Propiedades</button>
                                </form>
                            </li>
                            
                            <li>
                                <form action="buscar.php" method="POST">
                                    <input type="hidden" name="plantas">
                                    <button type="submit" name='quieroBuscar'>Buscar Plantas</button>
                                </form>
                            </li>
                        </ul>
                    </li>
                    
                    <li class="categoria"><a>Borrar</a>
                        <ul class="submenu">
                            <li>
                                <form action="borrar.php" method="POST">
                                    <input type="hidden" name="propiedades">
                                    <button type="submit" name='borrar'>Borrar Propiedades</button>
                                </form>
                            </li>
                            
                            <li>
                                <form action="borrar.php" method="POST">
                                    <input type="hidden" name="plantas">
                                    <button type="submit" name='borrar'>Borrar Planta</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </header>
        
        <article>
            <?php
                if (isset($_POST['plantas'])) {
                    
                    /*
                        No me gusta tener que mostrar la clave primaria 
                        pero no queda otra o el administrador tendrá problemas para insertar datos, 
                        además de que se supone que es un usuario autorizado 
                        así que no deberia haber problemas en que sepa esa información de la BD.
                    */
                    $consulta = "SELECT * FROM PLANTAS;";
    
                    echo "<p style='margin:1%;'></p>";

                    $salida = mostrarSelect ($conector, $consulta, "Plantas");
                    echo $salida;

                } elseif (isset($_POST['propiedades'])) {
                    
                    $consulta = "SELECT IdPlanta, Nombre_comun as Nombre, Propiedad, Potencia FROM PLANTAS INNER JOIN PROPIEDADES ON Id=IdPlanta;";
    
                    $salida = mostrarSelect ($conector, $consulta);
                    echo $salida;

                }
            ?>
        </article>

        <footer>
            <form method="POST">
                <button type="submit" name="cerrarSesion">Salir</button>
            </form>
        </footer>
    </body>


</html>




<?php
    } elseif (isset($_POST['cerrarSesion'])) {
        unset ($_SESSION);
        session_destroy();

        echo "<script type='text/javascript'>window.location.href = 'login.php';</script>";
    } else {
        $_SESSION['alarma'] = "Detectado intento de saltarse el logueo";
                
        echo "<script type='text/javascript'>window.location.href = 'login.php';</script>";
    }
?>