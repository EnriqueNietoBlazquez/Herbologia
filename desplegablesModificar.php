<?php
    session_start();
    
    if ( isset($_SESSION['autorizado']) && ( isset($_POST['modificar']) || isset($_POST['plantaElegida']) || isset($_SESSION['seleccionInvalida']) ) ) {
        /*
            No tiene sentido importar la libreria y crear la conexión si el acceso es ilegal, 
            por ello he esperado a que la condicion se cumpliera para hacer esas cosas,
            de hecho así es más seguro
        */
        require 'basesDeDatos.lib.php';
        require 'manejoDeFormularios.lib.php';

        $conector = mysqli_connect ("localhost", "root", "", "enriqueNieto");

        if (isset($_SESSION['seleccionInvalida'])) {
            echo "<script type='text/javascript'>alert('Error, no se admite seleccionar las instrucciones de uso');</script>";
            unset($_SESSION["seleccionInvalida"]);
            $_POST['propiedades'] = true;
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
                
				if (isset($_POST['propiedades']) || isset($_POST['plantaElegida'])) {

					$sentencia = "SELECT Id, Nombre_comun FROM PLANTAS LEFT JOIN PROPIEDADES ON Id = IdPlanta WHERE IdPlanta IS NOT NULL GROUP BY IdPlanta";
					$resultado = mysqli_query($conector, $sentencia);

					$numeroFilas = mysqli_num_rows($resultado);

					if ($numeroFilas > 0) {
						while($fila = mysqli_fetch_assoc($resultado)) {
							$plantas[] = $fila;
						}
						
                        ?>

                        <form action="desplegablesModificar.php" method="POST" id='formularioManejaDatosBD'>
                            <table>
                                <tr>
                                    <th>
                                        <label for="plantasDesplegable">Escoge una planta:</label>
                                    </th>
                                    
                                    <td>
                                        <select name="plantaElegida" id="plantasDesplegable" onchange="this.form.submit()">
                                            <option value="introduccion">Selecciona planta</option>';

                                            <?php
                                                foreach ($plantas as $planta) {
                                                    $id = $planta["Id"];
                                                    $nombre = $planta["Nombre_comun"];
                                                    //Podria ahorrarme el campo Id ya que Nombre_comun no se va a repetir pero se supone que está prohibido no usar la clave primaria aunque Nombre_comun sea candidata a clave primaria.
                                                    ?>
                                                    
                                                    <option value="<?php echo $id; ?>" <?php if (isset($_POST['plantaElegida'])) {if ($_POST['plantaElegida'] == $id) {echo "selected='selected'";}} ?>>
                                                        <?php
                                                            echo $nombre;
                                                        ?>
                                                    </option>

                                                    <?php
                                                }
                                            ?>
                                        </select>
                                    </td>
                                </tr>

                                <?php
                                    if (! empty($_POST['plantaElegida'])) {
                                                
                                        $plantaElegida = $_POST['plantaElegida'];
                                        $sentencia = "SELECT Propiedad FROM PROPIEDADES WHERE IdPlanta = $plantaElegida";
                                        $resultado_propiedades = mysqli_query($conector, $sentencia);
                                        
                                        $numeroFilas = mysqli_num_rows($resultado);
                                        
                                        echo "<tr>";

                                        if ($numeroFilas > 0) {
                                            echo "<th>";
                                                                                
                                            echo '<label for="propiedadesDesplegable">Propiedades:</label>';
                                    
                                            echo "</th>";

                                            echo "<td>";
                                        
                                            echo '<select name="propiedadElegida" id="propiedadesDesplegable">';
                                                
                                            while($fila = mysqli_fetch_assoc($resultado_propiedades)) {
                                                echo '<option value="' . $fila["Propiedad"] . '">' . $fila["Propiedad"] . '</option>';
                                            }
                                                    
                                            echo '</select>';
                                            
                                            echo "</td>";                                            
                                        } else {
                                            echo '<th>No hay propiedades disponibles para esta planta.</th>';
                                        }

                                        echo "</tr>";
                                    }

                                    ?>
                            </table>

                            
                            <?php 
                                //Debo definir esta variable porque al cambiar a donde se envia el formulario, no se envia el name del button submit_
                                $_SESSION['vengoDePROPIEDADES'] = true; 
                            ?>

                            <button type="submit" onclick="enviarAOtroArchivo(event)">Enviar</button>
                        </form>

                        <?php
                    } else {
                        echo "<h2>Ningun resultado</h2>";
                    }

				} elseif (isset($_POST['plantas'])) {

					if (isset($_SESSION['vengoDePROPIEDADES'])) {
                        unset($_SESSION['vengoDePROPIEDADES']);
                    }

                    $opcionesDesplegable = "SELECT Id, Nombre_comun FROM PLANTAS";
					$resultado = mysqli_query($conector, $opcionesDesplegable);
					
					?>

					<form action="modificar.php" method="POST" id='formularioManejaDatosBD'>
						<table>
							<!--
								El label simplimente es como un p enlazado con el input 
								para que así; al clickear sobre este p, 
								sea como si se clickea el input, 
								lo cual da más...intuitividad al sitio
							-->
							<tr>
								<th>
									<label for="plantasDesplegable">Escoge una planta:</label>
								</th>

								<td>
									<select name="plantaElegida" id="plantasDesplegable">
							
										<?php
											$numeroFilas = mysqli_num_rows($resultado);

											if ($numeroFilas > 0) {    
												/*
													Sin este if, la primera vez funcionará pero luego no va a volver a funcionar sin recargar
												*/
												if (isset($plantas)) {
													unset($plantas);
												}
												
												while($fila = mysqli_fetch_assoc($resultado)) {
													$plantas[] = $fila;
												}
															
												foreach ($plantas as $planta) {
													//Podria ahorrarme el campo Id ya que Nombre_comun no se va a repetir pero se supone que está prohibido no usar la clave primaria aunque Nombre_comun sea candidata a clave primaria.
													echo '<option value="' . $planta["Id"] . '">' . $planta["Nombre_comun"] . '</option>';
												}

											} else {
												echo '<option value="">No hay plantas disponibles</option>';
											}
										?>

									</select>
								</td>
							</tr>
						</table>
						
						<button type="submit" name="vengoDePLANTAS">Enviar</button>
					</form>
					
					<?php

                }
                          
            ?>
        </article>

        <footer>
            <form method="POST">
                <button type="submit" name="cerrarSesion">Salir</button>
            </form>
        </footer>

        <script type="text/javascript">
            function enviarAOtroArchivo(event) {
                //Evito que se aplique el envie del formulario hecho con onchange="this.form.submit():
                event.preventDefault();
                //event significa cualquier tipo de evento, debo decirle que ignore ese evento, o sea un click en este caso, para que me de tiempo a hacer los cambios

                //Cambio a donde debe enviar los datos:
                var formulario = document.getElementById('formularioManejaDatosBD');
                formulario.action = 'modificar.php';

                //Hago que se envien los datos:
                formulario.submit();
            }
        </script>

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