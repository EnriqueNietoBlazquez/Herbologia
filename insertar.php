<?php
    session_start();
    
    if ( isset($_SESSION['autorizado']) && (isset($_POST['insertar']) || isset($_POST['insertarPlanta']) || isset($_POST['insertarPropiedad'])) ) {
        /*
            No tiene sentido importar la libreria y crear la conexión si el acceso es ilegal, 
            por ello he esperado a que la condicion se cumpliera para hacer esas cosas,
            de hecho así es más seguro
        */
        require 'basesDeDatos.lib.php';
        require 'manejoDeFormularios.lib.php';
        
        $conector = mysqli_connect ("localhost", "root", "", "enriqueNieto");


        /*
            O hago esto (o la opción insegura de usar inputs de tipo hidden), 
            o el formulario desaparecerá al momento de insertar los datos:
        */
        
        if (isset($_POST['insertarPlanta'])) {
            $_POST['plantas'] = true;
        } elseif (isset($_POST['insertarPropiedad'])) {
            $_POST['propiedades'] = true;
        }
?>




<!DOCTYPE html>



<html lang="es">


    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Insertar</title>
        <link rel="stylesheet" href="menuYFondo.css" type="text/css">
        <link rel="stylesheet" href="tablas.css" type="text/css">
        <link rel="stylesheet" href="busquedas.css" type="text/css">
        <link rel="stylesheet" href="formulariosManejaDatos.css" type="text/css">
    </head>


    <body>

        <header>
            <h1>Insertar</h1>

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
                if (! isset($_SESSION['contadorErrores'])) {
                    $_SESSION['contadorErrores'] = 0;
                }

                if (isset($_POST['propiedades'])) {
                    
                    ?>
                    
                    <form method="POST" id="formularioManejaDatosBD" class="propiedades">
                        <table>
                        
                            <tr>
                                <th>
                                    <label for="IdPlanta">Planta de la que deseas informar:</label>
                                </th>

                                <td>
                                    <select name="IdPlanta" id="IdPlanta">
                                        <?php
                                            $consulta = "SELECT Id, Nombre_comun FROM PLANTAS";
                                            $resultado = mysqli_query($conector, $consulta);

                                            while ($fila = mysqli_fetch_assoc($resultado)) {
                                                ?>
                                                
                                                <option value='<?php echo $fila['Id']; ?>' <?php if (isset($_POST['IdPlanta'])) { if ($_POST['IdPlanta'] == $fila['Id']) {echo "selected='selected'";}} ?>>
                                                    <?php echo $fila['Nombre_comun']; ?>
                                                </option>
                                                
                                                <?php 

                                                $listaPlantasExistentes[] = $fila['Id'];
                                            }
                                        ?>
                                    </select>
                                </td>

                                <?php
                                    if (isset($_POST['insertarPropiedad'])) {
                                        if (! recorrerArrayBuscandoValor($_POST['IdPlanta'], $listaPlantasExistentes)) {
                                            echo "<td>";
                                                echo "<p class='error'>Detectado intento de ataque</p>";
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
                                        }
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Propiedad">Nueva Propiedad:</label>
                                </th>

                                <td>
                                    <textarea name="Propiedad" id="Propiedad" rows="4" cols="50" required value="<?php if 
                                    (isset($_POST['insertarPropiedad'])) { 
                                        if (isset($_POST['Propiedad'])) {
                                            echo $_POST['Propiedad'];
                                        } 
                                    }?>"></textarea>
                                </td>

                                <?php
                                    if (isset($_POST['insertarPropiedad'])) {
                                        if (! comprobarTexto($_POST['Propiedad'])) {
                                            echo "<td>";
                                                echo comprobarTexto($_POST['Propiedad']);
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
                                        }
                                    }
                                    ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Potencia">Potencia de la propiedad de dicha planta (1-10):</label>
                                </th>

                                <td>
                                    <input type="number" name="Potencia" id="Potencia" min=1 max=10 required value="<?php if 
                                    (isset($_POST['insertarPropiedad'])) { 
                                        if (isset($_POST['Potencia'])) {
                                            echo $_POST['Potencia'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPropiedad'])) {
                                        if (! comprobarNumeroRango ($_POST['Potencia'])) {
                                            echo "<tr>";
                                                echo "<td>";
                                                    echo comprobarNumeroRango ($_POST['Potencia']);
                                                echo "</td>";
                                            echo "</tr>";

                                            $_SESSION['contadorErrores'] += 1;
                                        }
                                    }
                                ?>
                            </tr>
                        
                        </table>
                        
                        <button type="submit" name="insertarPropiedad">Insertar Propiedad</button>
                    </form>

                    
                    <?php
                    /*
                        Aunque parezcan que hay errores en la tabulación; no los hay. 
                        Uso el cierre y apertura de PHP como preparativos 
                        para el formulario y para volver a lo normal una vez termina el HTML.
                    */
                    
                    if (isset($_POST['insertarPropiedad']) && $_SESSION['contadorErrores'] == 0) {
                        unset($_SESSION['contadorErrores']);

                        traducirArrayIndexadoAVariables ($_POST);
                        
                        $valores = [$IdPlanta, $Propiedad, $Potencia];
                        
                        insertarEnBD($conector, "PROPIEDADES", $valores);
                    }

                } elseif (isset($_POST['plantas'])) {
                    /*
                        Aunque parezcan que hay errores en la tabulación; no los hay. 
                        Uso el cierre y apertura de PHP como preparativos 
                        para el formulario y para volver a lo normal una vez termina el HTML.
                    */
                    ?>
                    
                    <form method="POST" enctype="multipart/form-data" id="formularioManejaDatosBD">
                        <table>
                            
                            <tr>
                                <th>
                                    <label for="IDPlanta">ID de la nueva planta:</label>
                                </th>

                                <td>
                                    <input type="number" name="Id" id="IDPlanta" required min="1" value="<?php if 
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Id'])) {
                                            echo $_POST['Id'];
                                        } 
                                    }?>"> 
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        $consulta = "SELECT Id FROM PLANTAS";
                                        $resultado = mysqli_query($conector, $consulta);
                                        $repetido = false;
                                    
                                        while ($fila = mysqli_fetch_assoc($resultado)) {
                                            foreach ($fila as $idExistente) {
                                                if ($_POST['Id'] == $idExistente) {
                                                    $repetido = true;
                                                }
                                            }                                             
                                        }
                                        
                                        if (! comprobarNumero ($_POST['Id'])) {
                                            $_SESSION['contadorErrores'] += 1;
                                            
                                            echo "<td>";
                                                echo comprobarNumero ($_POST['Id']);
                                            echo "</td>";
                                        } elseif ($repetido) {
                                            $_SESSION['contadorErrores'] += 1;

                                            echo "<td>";
                                                echo "<p class='error'>Lo siento pero ese ID está repetido.",
                                                "Se que ahora no te gustará no usar autoincrement pero me lo agradeceras cuando tengas IDs vacios que no puedas llenar, o al menos, no facilmente</p>";
                                            echo "</td>";
                                        }
                                        
                                    }
                                    
                                ?>
                            </tr>

                            <tr>
                                <th>
                                    <label for="Nombre_cientifico">Nombre Científico:</label>
                                </th>

                                <td>
                                    <input type="text" name="Nombre_cientifico" id="Nombre_cientifico" required value="<?php if 
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Nombre_cientifico'])) {
                                            echo $_POST['Nombre_cientifico'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        if (! comprobarTexto ($_POST['Nombre_cientifico'])) {
                                            echo "<td>";
                                                echo comprobarTexto ($_POST['Nombre_cientifico']);
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
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
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Nombre_comun'])) {
                                            echo $_POST['Nombre_comun'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        if (! comprobarTexto ($_POST['Nombre_comun'])) {
                                            echo "<td>";
                                                echo comprobarTexto ($_POST['Nombre_comun']);
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
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
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Riego'])) {
                                            echo $_POST['Riego'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        if (! comprobarTexto ($_POST['Riego'])) {
                                            echo "<td>";
                                                echo comprobarTexto ($_POST['Riego']);
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
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
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Temperatura'])) {
                                            echo $_POST['Temperatura'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        if (! comprobarTexto ($_POST['Temperatura'])) {
                                            echo "<td>";
                                                echo comprobarTexto ($_POST['Temperatura']);
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
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
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Luz'])) {
                                            echo $_POST['Luz'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        if (! comprobarTexto ($_POST['Luz'])) {
                                            echo "<td>";
                                                echo comprobarTexto ($_POST['Luz']);
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
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
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Fertilizacion'])) {
                                            echo $_POST['Fertilizacion'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        if (! comprobarTexto ($_POST['Fertilizacion'])) {
                                            echo "<td>";
                                                echo comprobarTexto ($_POST['Fertilizacion']);
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
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
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Zona_nativa'])) {
                                            echo $_POST['Zona_nativa'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        if (! comprobarTexto ($_POST['Zona_nativa'])) {
                                            echo "<td>";
                                                echo comprobarTexto ($_POST['Zona_nativa']);
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
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
                                    (isset($_POST['insertarPlanta'])) { 
                                        if (! empty($_POST['Apodos'])) {
                                            echo $_POST['Apodos'];
                                        } 
                                    }?>">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        
                                        if (is_numeric ($_POST['Apodos'])) {
                                            echo "<td>";
                                                echo "<p class='error'>Error, no se admiten números</p>";
                                            echo "</td>";

                                            $_SESSION['contadorErrores'] += 1;
                                        }
                                        
                                    }
                                ?>
                            </tr>
                            
                            <tr>
                                <th>
                                    <label for="Aspecto">Aspecto:</label>
                                </th>

                                <td>
                                    <input type="file" name="Aspecto" id="Aspecto">
                                </td>

                                <?php
                                    if (isset($_POST['insertarPlanta'])) {
                                        echo "<td>";

                                        if ($_FILES['Aspecto']['error'] != UPLOAD_ERR_OK) {
                                            echo "<p class='error'>Se requiere imagen</p>";
                                            $_SESSION['contadorErrores'] += 1;
                                        } else {
                                            if (! getimagesize($_FILES['Aspecto']['tmp_name'])) {
                                                echo "<p class='error'>Eso, no es una imagen</p>";
                                                $_SESSION['contadorErrores'] += 1;
                                            } else {
                                                echo "<p style='color:green; font-weight:bold;'>Detectado archivo subido, lamento no poder mantenertelo en el formulario. ",
                                                "Los navegadores no lo permiten, para así evitar que los sitios web maliciosos ",
                                                "puedan acceder a archivos en el ordenador del usuario sin su consentimiento explícito.</p>";
                                            }
                                        }
                
                                        echo "</td>";
                                    }
                                ?>
                            </tr>
                    
                        </table>

                        <button type="submit" name="insertarPlanta">Insertar Planta</button>
                    </form>
                
                    <?php
                    if (isset($_POST['insertarPlanta']) && $_SESSION['contadorErrores'] == 0) {
                        unset($_SESSION['contadorErrores']);
                        
                        guardarImagenSubida($_FILES['Aspecto']);
                        $Aspecto = $_FILES['Aspecto']['name'];
                
                        traducirArrayIndexadoAVariables ($_POST);

                        $valores = [$Id, $Nombre_cientifico, $Nombre_comun, $Riego, $Temperatura, $Luz, $Fertilizacion, $Zona_nativa, $Apodos, $Aspecto];
                        insertarEnBD($conector, "PLANTAS", $valores);
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