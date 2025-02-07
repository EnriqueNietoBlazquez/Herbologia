<?php
    session_start();
    
    if (isset($_SESSION['resultadoBorrar'])) {
        $_POST['borrar'] = true;

        if (isset($_SESSION['heBorradoPropiedad'])) {
            $_POST['propiedades'] = true;
            unset($_SESSION['heBorradoPropiedad']);
        } elseif (isset($_SESSION['heBorradoPlanta'])) {
            $_POST['plantas'] = true;
            unset($_SESSION['heBorradoPlanta']);
        }
    }

    if ( isset($_SESSION['autorizado']) && ( isset($_POST['borrar']) || isset($_POST['vengoDePROPIEDADES']) || isset($_POST['PLANTAS']) || isset($_SESSION['resultadoBorrar']) ) ) {
        /*
            No tiene sentido importar la libreria y crear la conexión si el acceso es ilegal, 
            por ello he esperado a que la condicion se cumpliera para hacer esas cosas,
            de hecho así es más seguro.
        */
        require 'basesDeDatos.lib.php';
        $conector = mysqli_connect ("localhost", "root", "", "enriqueNieto");

        if (isset($_SESSION['resultadoBorrar'])) {
            $_POST['borrar'] = "muestrameTabla";
        }
?>




<!DOCTYPE html>



<html lang="es">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Borrar</title>
        <link rel="stylesheet" href="menuYFondo.css" type="text/css">
        <link rel="stylesheet" href="tablas.css" type="text/css">
        <link rel="stylesheet" href="busquedas.css" type="text/css">
    </head>


    <body>

        <header>
            <h1>Borrar</h1>

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
                if (isset($_POST['vengoDePROPIEDADES'])) {
                
                    /*
                        Recapitulación para no perdernos:
                            $_POST['borrar'] contiene a $numRegistro, que es el registro que quiero borrar sin necesitar restarle o sumarle nada.
                            El array con los valores de los campos es $_SESSION["clavePrimaria"][$_POST['borrar']] al que hay que restarle 1, o sea la primera parte está en 0.
                            Solamente me he llevado la clave primaria, no toda la fila

    
                        Copiado de otro fichero para pensar qué es y donde está cada cosa:
                            $_SESSION["clavePrimaria"][$numRegistro][] = $valor;
                            "<button type='submit' name='borrar' value='$numRegistro'>Borrar</button>";
                            $_SESSION["camposClavePrimaria"][] = $valor;
                    */

                    $campos = capturaCabeceras ($conector);

                    $condiciones = hacerCondicionesSentencia ($campos[0], $_SESSION["clavePrimaria"][$_POST['borrar']]);
                    $sentencia = "DELETE FROM PROPIEDADES WHERE $condiciones;";
                    
                    unset($_SESSION["camposClavePrimaria"]);
                    unset($_SESSION["clavePrimaria"]);

                    $_SESSION['resultadoBorrar'] = "<h2>";
                    
                    if (mysqli_query($conector, $sentencia)) {
                        
                        $numFilas = mysqli_affected_rows($conector);
                        
                        if ($numFilas == 1) {
                            $_SESSION['resultadoBorrar'] .= "Registro eliminado con éxito";
                        } elseif ($numFilas > 1) {
                            $_SESSION['resultadoBorrar'] .= "Se han borrado " . $numFilas . " registros";
                        } elseif ($numFilas < 1) {
                            $_SESSION['resultadoBorrar'] .= "Error, no se ha borrado nada";
                        }
                        
                    } else {
                        $error_message = "Error al eliminar el registro: " . mysqli_error($conector);
                        $_SESSION['resultadoBorrar'] .= $error_message;
                        /*Para diagnostico: 
                        $_SESSION['resultadoBorrar'] .= $condiciones;
                        $_SESSION['resultadoBorrar'] .= $sentencia;*/
                    }

                    $_SESSION['resultadoBorrar'] .= "</h2>";
                    $_SESSION['heBorradoPropiedad'] = true;

                    echo "<script type='text/javascript'>window.location.href = 'borrar.php';</script>";

                } elseif (isset($_POST['vengoDePLANTAS'])) {
                
                    /*
                        Recapitulación para no perdernos:
                            $_POST['borrar'] contiene a $numRegistro, que es el registro que quiero borrar sin necesitar restarle o sumarle nada.
                            El array con los valores de los campos es $_SESSION["clavePrimaria"][$_POST['borrar']] al que hay que restarle 1, o sea la primera parte está en 0.
                            Solamente me he llevado la clave primaria, no toda la fila

    
                        Copiado de otro fichero para pensar qué es y donde está cada cosa:
                            $_SESSION["clavePrimaria"][$numRegistro][] = $valor;
                            "<button type='submit' name='borrar' value='$numRegistro'>Borrar</button>";
                            $_SESSION["camposClavePrimaria"][] = $valor;
                    */
                
                    $campos = capturaCabeceras ($conector, "PROPIEDADES", 1);
                    $condiciones = hacerCondicionesSentencia ($campos[0], $_SESSION["clavePrimaria"][$_POST['borrar']]);
                    
                    $sentenciaBusquedaPropiedades = "SELECT count(*) FROM PROPIEDADES WHERE $condiciones";
                    $busquedaPropiedades = mysqli_query($conector, $sentenciaBusquedaPropiedades);
                    
                    while($cantidadPropiedadesSinDepurar = mysqli_fetch_assoc($busquedaPropiedades)) {
                        $cantidadPropiedades = $cantidadPropiedadesSinDepurar;
                    }

                    
                    if ($cantidadPropiedades != 0) {
                        $_SESSION['resultadoBorrar'] = "<h2>Error, no se puede borrar una planta si todavia tiene propiedades almacenadas en esta BD.</h2>";
                    } else {
                        $campos = capturaCabeceras ($conector, "PLANTAS", 1);
                        $condiciones = hacerCondicionesSentencia ($campos[0], $_SESSION["clavePrimaria"][$_POST['borrar']]);
                        $sentencia = "DELETE FROM PLANTAS WHERE $condiciones;";

                        
                        unset($_SESSION["camposClavePrimaria"]);
                        unset($_SESSION["clavePrimaria"]);


                        $_SESSION['resultadoBorrar'] = "<h2>";
                        
                        if (mysqli_query($conector, $sentencia)) {
                            
                            $numFilas = mysqli_affected_rows($conector);
                            
                            if ($numFilas == 1) {
                                $_SESSION['resultadoBorrar'] .= "Registro eliminado con éxito";
                            } elseif ($numFilas > 1) {
                                $_SESSION['resultadoBorrar'] .= "Se han borrado " . $numFilas . " registros";
                            } elseif ($numFilas < 1) {
                                $_SESSION['resultadoBorrar'] .= "Error, no se ha borrado nada";
                            }
                            
                        } else {
                            $error_message = "Error al eliminar el registro: " . mysqli_error($conector);
                            $_SESSION['resultadoBorrar'] .= $error_message;
                            /*Para diagnostico: 
                            $_SESSION['resultadoBorrar'] .= $condiciones;
                            $_SESSION['resultadoBorrar'] .= $sentencia;*/
                        }

                        $_SESSION['resultadoBorrar'] .= "</h2>";
                    }


                    $_SESSION['heBorradoPlanta'] = true;
                    echo "<script type='text/javascript'>window.location.href = 'borrar.php';</script>";

                } elseif (isset($_POST['borrar'])) {
                    
                    if (! is_numeric($_POST['borrar'])) {
                        if (isset($_POST['propiedades'])) {
                    
                            $consulta = "SELECT IdPlanta, Propiedad, Nombre_comun as Nombre, Potencia FROM PLANTAS INNER JOIN PROPIEDADES ON Id=IdPlanta;";
            
                            $salida = selectConBotones ($conector, $consulta, "Propiedades", "imagenes/", 2);
                            echo $salida;
        
                        } elseif (isset($_POST['plantas'])) {
                            
                            /*
                                No me gusta tener que mostrar la clave primaria 
                                pero no queda otra o el administrador tendrá problemas para insertar datos, 
                                además de que se supone que es un usuario autorizado 
                                así que no deberia haber problemas en que sepa esa información de la BD
                            */
                            $consulta = "SELECT * FROM PLANTAS;";
            
                            echo "<p style='margin:1%;'></p>";
        
                            $salida = selectConBotones ($conector, $consulta, "Plantas:", "imagenes/", 1, "PLANTAS");
                            echo $salida;
        
                        }

                        if (isset($_SESSION['resultadoBorrar'])) {
                            echo $_SESSION['resultadoBorrar'];
                            unset($_SESSION['resultadoBorrar']);
                        }
                    }

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