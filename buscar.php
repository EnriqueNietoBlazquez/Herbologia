<?php
    session_start();
    
    if ( isset($_SESSION['autorizado']) && ( isset($_POST['quieroBuscar']) || isset($_POST['buscar']) ) ) {
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
        <title>Buscar</title>
        <link rel="stylesheet" href="menuYFondo.css" type="text/css">
        <link rel="stylesheet" href="tablas.css" type="text/css">
        <link rel="stylesheet" href="busquedas.css" type="text/css">
        <style>
            html {
                min-height:100vh;
            }

            body {
                display:flex;
                justify-content: space-between;
                flex-direction:column;
                min-height:100vh;
            }

            header {
                width: 100%;
            }

            section {
                width: 100%;
            }

            footer {
                width: 100%;
            }
        </style>
    </head>


    <body>

        <header>
            <h1>Buscar</h1>

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
        
        <section>
            <article>
                <?php
                    if (isset($_POST['propiedades'])) {
                        
                        ?>
                        
                        <form method='POST'>
                            <h2>¿Qué propiedad quieres buscar?</h2> 
                            
                            <input type='text' name='busquedaPropiedades' required>
                            <input type='hidden' name='propiedades'>
                            
                            <button type='submit' name='buscar'>Buscar</button>
                        </form>

                        <?php

                    } elseif (isset($_POST['plantas'])) {
                        
                        ?>
                        
                        <form method='POST'>
                            <h2>¿Qué planta quieres buscar?</h2>
                            
                            <input type='text' name='busquedaPlantas' required>
                            <input type='hidden' name='plantas'>
                            
                            <button type='submit' name='buscar'>Buscar</button>
                        </form>

                        <?php
                    }
                ?>
            </article>

            <?php
                if (isset($_POST['buscar'])) {
            ?>

            <article id="segundo">
                <?php        
                    if (isset($_POST['busquedaPlantas'])) {
                        if (! empty($_POST['busquedaPlantas'])) {
							$busqueda = $_POST['busquedaPlantas'];

							$consulta = "SELECT * FROM PLANTAS WHERE Nombre_cientifico LIKE '%$busqueda%' OR Nombre_comun LIKE '%$busqueda%' OR Riego LIKE '%$busqueda%' OR Temperatura LIKE '%$busqueda%' OR Luz LIKE '$busqueda' OR Fertilizacion LIKE '%$busqueda%' OR Zona_nativa LIKE '%$busqueda%' OR Apodos LIKE '%$busqueda%';";

							$salida = selectConBotones ($conector, $consulta, "Plantas:");
							echo $salida;
						}
                    } elseif (isset($_POST['busquedaPropiedades'])) {
                        if (! empty($_POST['busquedaPropiedades'])) {
                            $busqueda = $_POST['busquedaPropiedades'];
                                    
                            $consulta = "SELECT IdPlanta, Propiedad, Nombre_comun, Potencia FROM PLANTAS INNER JOIN PROPIEDADES ON Id=IdPlanta WHERE Nombre_comun LIKE '%$busqueda%' OR Propiedad LIKE '%$busqueda%' OR Potencia LIKE '%$busqueda%';";
                            
                            $salida = selectConBotones ($conector, $consulta, "Propiedades", "imagenes/", 2);
                            echo $salida; 
                        }
                    }
                        
                    /*
                        Para diagnostico:
                        echo $consulta;
                    */
                ?>
            </article>

            <?php
                }
            ?>
        </section>

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