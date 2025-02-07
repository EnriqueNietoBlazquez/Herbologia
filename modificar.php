<?php
    session_start();
    
    if (isset($_SESSION['vengoDePROPIEDADES'])) {
        $_POST['vengoDePROPIEDADES'] = true;
        unset($_SESSION['vengoDePROPIEDADES']);
    }
    
    if ($_POST['plantaElegida'] == "introduccion") {
        $_SESSION['seleccionInvalida'] = true;
        echo "<script type='text/javascript'>window.location.href = 'login.php';</script>";
        
    }

    if ( isset($_SESSION['autorizado']) && ( isset($_POST['heModificadoPropiedad']) || isset($_POST['heModificadoPlanta']) || isset($_POST['vengoDePROPIEDADES']) || isset($_POST['vengoDePLANTAS'])) ) {
        /*
            No tiene sentido importar la libreria y crear la conexión si el acceso es ilegal, 
            por ello he esperado a que la condicion se cumpliera para hacer esas cosas,
            de hecho así es más seguro
        */
        require 'basesDeDatos.lib.php';
        require 'manejoDeFormularios.lib.php';

        $conector = mysqli_connect ("localhost", "root", "", "enriqueNieto");

        if (isset($_POST['heModificadoPlanta'])) {
            $_POST['vengoDePLANTAS'] = true;
        } elseif (isset($_POST['heModificadoPropiedad'])) {
            $_POST['vengoDePROPIEDADES'] = true;
        }
?>




<!DOCTYPE html>



<html lang="es">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Modificar</title>
        <link rel="stylesheet" href="menuYFondo.css" type="text/css">
        <link rel="stylesheet" href="tablas.css" type="text/css">
        <link rel="stylesheet" href="busquedas.css" type="text/css">
        <link rel="stylesheet" href="formulariosManejaDatos.css" type="text/css">
    </head>


    <body>

        <header>
            <h1>Modificar</h1>

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
                
                $contadorErrores = 0;

                if (isset($_POST['vengoDePROPIEDADES'])) {

                    ?>

                    <form method='POST' id='formularioManejaDatosBD'>
                        <h2>Aviso; se permiten valores NULL para evitar tener que poner datos que no cambiaras y arriesgarte a confundirte, pero no datos invalidos</h2>

                        <table>
                                
                            <tr>
                                <th>
                                    <label for='IdPlanta'>Planta de la que deseas informar:</label>
                                </th>
                                
                                <td>
                                    <?php
                                        if (isset($_SESSION["clavePrimaria"])) {
                                            $_POST['plantaElegida'] = $_SESSION["clavePrimaria"][$_POST['modificar']][0];
                                        } elseif (! isset($_POST['plantaElegida'])) {
                                            $_POST['plantaElegida'] = $_POST['IdPlanta'];
                                        }
                                    ?>

                                    <input type='number' name='IdPlanta' value='<?php echo $_POST['plantaElegida']; ?>' placeholder='<?php echo $_POST['plantaElegida']; ?>' readonly id='IdPlanta'>
                                </td>
                            </tr>

                            <tr>
                                <th>
                                    <label for='Propiedad'>Nueva Propiedad:</label>
                                </th>
                                
                                <td>
                                    <?php
                                        if (isset($_SESSION["clavePrimaria"])) {
                                            $_POST['propiedadElegida'] = $_SESSION["clavePrimaria"][$_POST['modificar']][1];
                                            unset ($_SESSION["clavePrimaria"]);
                                        } elseif (! isset($_POST['propiedadElegida'])) {
                                            $_POST['propiedadElegida'] = $_POST['Propiedad'];
                                        }
                                    ?>

                                    <input type='text' name='Propiedad' value='<?php echo $_POST['propiedadElegida']; ?>' placeholder='<?php echo $_POST['propiedadElegida']; ?>' readonly id='Propiedad'>
                                </td>
                            </tr>

                            <tr>
                                <th> 
                                    <label for="Potencia">Potencia de la propiedad de dicha planta (1-10):</label>
                                </th>

                                <td>
                                    <?php
                                        $id = $_POST['plantaElegida'];
                                        $propiedad = $_POST['propiedadElegida'];

                                        $sentencia = "SELECT Potencia FROM PROPIEDADES WHERE IdPlanta = $id AND Propiedad = '$propiedad'";
                                        $resultado = mysqli_query($conector, $sentencia);
                                            
                                        $potencia = mysqli_fetch_array($resultado);
                                        unset ($potencia[0]);
                                    ?>
                                        
                                        <select name="Potencia" id="Potencia">
                                        <?php
                                            for ($i = 1; $i <= 10; $i++) { 
                                                ?>
                                                
                                                <option value='<?php echo $i; ?>' <?php if (isset($_POST['Potencia'])) { 
                                                    if ($_POST['Potencia'] == $i) {
                                                        echo "selected='selected'";
                                                    }
                                                } elseif ($potencia['Potencia'] == $i) {
                                                    echo "selected='selected'";
                                                } 
                                                ?>>
                                                    <?php echo $i; ?>
                                                </option>
                                                
                                                <?php

                                            }
                                        ?>
                                    </select>

                                
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPropiedad'])) {
                                        if ($_POST['Potencia'] <= 0 || $_POST['Potencia'] >= 11) {
                                            $contadorErrores = 0;

                                            echo "<td>";
                                                echo "<p>Detectado intento de ataque</p>";
                                            echo "</td>";
                                        }
                                    }
                                ?>
                                
                            </tr>

                        </table>

                        <button type="submit" name="heModificadoPropiedad">Modificar Propiedad</button>

                    </form>

                    <?php

                    if (isset($_POST['heModificadoPropiedad'])) {
                        $nombresCamposControl = array("IdPlanta", "Propiedad");
                        $valoresCamposControl = array($_POST['IdPlanta'], $_POST['Propiedad']);
                        
                        unset($_POST['IdPlanta'], $_POST['Propiedad'], $_POST['heModificadoPropiedad'], $_POST['vengoDePROPIEDADES'], $_POST['plantaElegida'], $_POST['propiedadElegida']);

                        $nuevosValores = parametrosAModificar($_POST, ["Potencia"]);

                        $condiciones = hacerCondicionesSentencia ($nombresCamposControl, $valoresCamposControl);

                        $sentencia = "UPDATE PROPIEDADES SET $nuevosValores WHERE $condiciones;";

                        if (mysqli_query($conector, $sentencia)) {
                            
                            $numFilas = mysqli_affected_rows($conector);

                            if ($numFilas == 1) {
                                echo "<h2>Registro modificado con éxito.<h2>";
                            } elseif ($numFilas > 1) {
                                echo "<h2>Se han modificado " . $numFilas . " registros.<h2>";
                            } elseif ($numFilas < 1) {
                                echo "<h2>Error, no se ha modificado nada. Quizas es que has puesto el valor actual así que todo sigue igual<h2>";
                            }
                            
                        } /*Para diagnostico: else {
                            $error_message = "Error al modificar el registro: " . mysqli_error($conector);
                            echo $error_message;
                            echo $condiciones;
                            echo $sentencia;
                        }*/
                    }

                } elseif (isset($_POST['vengoDePLANTAS'])) {
                    if (isset($_POST['plantaElegida'])) {
                        $id = $_POST['plantaElegida'];
                    } elseif (isset($_POST["Id"])) {
                        $id = $_POST['Id'];
                    } elseif (isset($_SESSION["clavePrimaria"])) {
                        $id = $_SESSION["clavePrimaria"][$_POST['modificar']][0];
                        unset($_SESSION['clavePrimaria']);
                    }
                        
                    $sentenciaObtencionDatosActuales = "SELECT * FROM PLANTAS WHERE Id = $id";
				    $resultado = mysqli_query($conector, $sentenciaObtencionDatosActuales);

                    $datosActuales = mysqli_fetch_array($resultado);
                    
                    foreach ($datosActuales as $indice => $valor) {
                        if (is_numeric($indice)) {
                            unset($datosActuales[$indice]);
                        }
                    }

                    ?>
                    
                    <form method='POST' enctype='multipart/form-data' id='formularioManejaDatosBD'>
                        <h2>Aviso; se permiten valores NULL para evitar tener que poner datos que no cambiaras y arriesgarte a confundirte, pero no datos invalidos</h2>
                    
                        <table>
                            <tr>
                                <th>
                                    <label for="Id">ID de la nueva planta:</label>
                                </th>

                                <td>
                                    <?php
                                        echo "<input type='text' name='Id' value='" . $datosActuales['Id'] . "' placeholder='" . $datosActuales['Id'] . "' id='Id' readonly>";
                                    ?>
                                </td>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Nombre_cientifico">Nombre Científico:</label>
                                </th>

                                <td>
                                    <input type="text" name="Nombre_cientifico" id="Nombre_cientifico" required value="<?php if 
                                    (isset($_POST['heModificadoPlanta'])) { 
                                        if (! empty($_POST['Nombre_cientifico'])) {
                                            echo $_POST['Nombre_cientifico'];
                                        } 
                                    } else {
                                        echo $datosActuales['Nombre_cientifico'];
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if (is_numeric($_POST['Nombre_cientifico'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Un número no es un nombre cientifico</p>";
                                            echo "</td>";

                                            $contadorErrores += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Nombre_comun">Nombre Común:</label>
                                </th>

                                <td>
                                    <input type="text" name="Nombre_comun" id="Nombre_comun" required value="<?php if 
                                    (isset($_POST['heModificadoPlanta'])) { 
                                        if (! empty($_POST['Nombre_comun'])) {
                                            echo $_POST['Nombre_comun'];
                                        } 
                                    } else {
                                        echo $datosActuales['Nombre_comun'];
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if (is_numeric($_POST['Nombre_comun'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Un número no es un nombre</p>";
                                            echo "</td>";

                                            $contadorErrores += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Riego">Riego:</label>
                                </th>

                                <td>
                                    <input type="text" name="Riego" id="Riego" required value="<?php if 
                                    (isset($_POST['heModificadoPlanta'])) { 
                                        if (! empty($_POST['Riego'])) {
                                            echo $_POST['Riego'];
                                        } 
                                    } else {
                                        echo $datosActuales['Riego'];
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if (is_numeric($_POST['Riego'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Pero eso son litros, son a la semana... o ¿Qué?</p>";
                                            echo "</td>";

                                            $contadorErrores += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Temperatura">Temperatura:</label>
                                </th>

                                <td>
                                    <input type="text" name="Temperatura" id="Temperatura" required value="<?php if 
                                    (isset($_POST['heModificadoPlanta'])) { 
                                        if (! empty($_POST['Temperatura'])) {
                                            echo $_POST['Temperatura'];
                                        } 
                                    } else {
                                        echo $datosActuales['Temperatura'];
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if (is_numeric($_POST['Temperatura'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Pero eso son grados como máximo, como mínimo, Celsius, fahrenheit... o ¿Qué?</p>";
                                            echo "</td>";

                                            $contadorErrores += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Luz">Luz:</label>
                                </th>

                                <td>
                                    <input type="text" name="Luz" id="Luz" required value="<?php if 
                                    (isset($_POST['heModificadoPlanta'])) { 
                                        if (! empty($_POST['Luz'])) {
                                            echo $_POST['Luz'];
                                        } 
                                    } else {
                                        echo $datosActuales['Luz'];
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if (is_numeric($_POST['Luz'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Pero eso son horas como máximo... o ¿Qué?</p>";
                                            echo "</td>";    

                                            $contadorErrores += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Fertilizacion">Fertilización:</label>
                                </th>

                                <td>
                                    <input type="text" name="Fertilizacion" id="Fertilizacion" required value="<?php if 
                                    (isset($_POST['heModificadoPlanta'])) { 
                                        if (! empty($_POST['Fertilizacion'])) {
                                            echo $_POST['Fertilizacion'];
                                        } 
                                    } else {
                                        echo $datosActuales['Fertilizacion'];
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if (is_numeric($_POST['Fertilizacion'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Pero eso ¿Cada cuanto tiempo o en cual momento? ¿Priorizando algún nutriente?</p>";
                                            echo "</td>";

                                            $contadorErrores += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Zona_nativa">Zona Nativa:</label>
                                </th>

                                <td>
                                    <input type="text" name="Zona_nativa" id="Zona_nativa" required value="<?php if 
                                    (isset($_POST['heModificadoPlanta'])) { 
                                        if (! empty($_POST['Zona_nativa'])) {
                                            echo $_POST['Zona_nativa'];
                                        } 
                                    } else {
                                        echo $datosActuales['Zona_nativa'];
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if (is_numeric($_POST['Zona_nativa'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Un número no es un lugar</p>";
                                            echo "</td>";

                                            $contadorErrores += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Apodos">Apodos:</label>
                                </th>

                                <td>
                                    <input type="text" name="Apodos" id="Apodos" value="<?php if 
                                    (isset($_POST['heModificadoPlanta'])) { 
                                        if (! empty($_POST['Apodos'])) {
                                            echo $_POST['Apodos'];
                                        } 
                                    } else {
                                        echo $datosActuales['Apodos'];
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if (is_numeric($_POST['Apodos'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Un número sin más no es un apodo, si al menos fuera algo como '7 ramas'</p>";
                                            echo "</td>";

                                            $contadorErrores += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Aspecto">Aspecto Actual:</label>
                                </th>

                                <td>
                                    <img src="\IAW\Proyecto\Entrega\imagenes/<?php echo $datosActuales['Aspecto']?>" alt="<?php echo $datosActuales['Aspecto']?>">
                                </td>
                            </tr>		
                            
                            <tr>
                                <th>
                                    <label for="Aspecto">Nuevo Aspecto:</label>
                                </th>

                                <td>
                                    <input type="file" name="Aspecto" id="Aspecto">
                                </td>

                                <?php
                                    if (isset($_POST['heModificadoPlanta'])) {
                                        if ($_FILES['Aspecto']['error'] == UPLOAD_ERR_OK) { 
                                            if (! getimagesize($_FILES['Aspecto']['tmp_name'])) {
                                                echo "<p class='error'>Eso, no es una imagen</p>";
                                                $_SESSION['contadorErrores'] += 1;
                                            } else {
                                                echo "<td>";                    
                                                    echo "<p>Detectado archivo subido, lamento no poder mantenertelo en el formulario. ",
                                                    "Los navegadores no lo permiten, para así evitar que los sitios web maliciosos ",
                                                    "puedan acceder a archivos en el ordenador del usuario sin su consentimiento explícito.</p>";                        
                                                echo "</td>";
                                            }
                                        }
                                    }
                            ?>
                            </tr>
                        </table>
                        
                        <button type='submit' name='heModificadoPlanta'>Modificar Planta</button>
	                </form>

                    <?php

                    if (isset($_POST['heModificadoPlanta'])) { 
                                        
                        if ($_FILES['Aspecto']['error'] == UPLOAD_ERR_OK) {
                            guardarImagenSubida ($_FILES['Aspecto']);
                            $_POST['Aspecto'] = $_FILES['Aspecto']['name'];
                        } 

                        $camposPosibleModificacion = array("Nombre_cientifico", "Nombre_comun", "Riego", "Temperatura", "Luz", "Fertilizacion", "Zona_nativa", "Apodos", "Aspecto");
                        $condiciones = hacerCondicionesSentencia (['Id'], $_POST['Id']);

                        unset($_POST['Id'], $_POST['heModificadoPlanta'], $_POST['vengoDePLANTAS'],);
                       
                        $nuevosValores = parametrosAModificar($_POST, $camposPosibleModificacion);
                        
                        $sentencia = "UPDATE PLANTAS SET $nuevosValores WHERE $condiciones;";
                        echo $sentencia;
                        if (mysqli_query($conector, $sentencia)) {
                            
                            $numFilas = mysqli_affected_rows($conector);
                            
                            if ($numFilas == 1) {
                                echo "<h2>Registro modificado con éxito<h2>";
                            } elseif ($numFilas > 1) {
                                echo "<h2>Se han modificado " . $numFilas . " registros <h2>";
                            } elseif ($numFilas < 1) {
                                echo "<h2>Error, no se ha modificado nada. Quizas es que has puesto el valor actual así que todo sigue igual<h2>";
                            }
                            
                        } /*Para diagnostico: else {
                            $error_message = "Error al modificar el registro: " . mysqli_error($conector);
                            echo $error_message;
                            echo $condiciones;
                            echo $sentencia;
                        }*/
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
        $_SESSION['alarma'] = "Detectado intento de ataque";
                
        echo "<script type='text/javascript'>window.location.href = 'login.php';</script>";
    }
?>