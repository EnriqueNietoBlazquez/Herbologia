<?php
    session_start();
    
    if (isset($_POST['cerrarSesion'])) {
        unset($_SESSION['autorizado']);
    }
    
    if (isset($_SESSION['autorizado'])) {

        if (isset($_SESSION['alarma'])) {
            unset($_SESSION['alarma']);
        }
?>




<!DOCTYPE html>



<html lang="es">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Menú</title>
        <link rel="stylesheet" href="menuYFondo.css" type="text/css">
        <style>
            footer {
                position: absolute;
                bottom: 0;
                width: 100%;
            }       
        </style>
    </head>


    <body>

        <header>
            <h1>Gestión de Información de Plantas</h1>

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
                                    <button type="submit" name='insertar'>Insertar Plantas</button>
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
                                    <button type="submit" name='modificar'>Modificar Plantas</button>
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
                                    <button type="submit" name='borrar'>Borrar Plantas</button>
                                </form>
                            </li>
                        </ul>
                    </li>

                </ul>
            </nav>
        </header>
        
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