<?php
    /*  
        Include solo genera una advertencia si el archivo no se encuentra
        Require genera un error fatal y detiene la ejecución

        El uso de ambas (desde el fichero receptor) es:
            <?php
                include 'basesDeDatos.lib.php'; 
                require 'basesDeDatos.lib.php';
            ?>
    */
?>



<?php
    function mostrarSelect ($conector, $sentenciaConsulta, $titulo = "Propiedades", $ubicacion = "imagenes/") {
        $consulta = mysqli_query($conector, $sentenciaConsulta); 
        
        $numeroFilas = mysqli_num_rows($consulta); 
        
        if ($numeroFilas == 0) { 
            echo "<h2 id='SinResultados'>No existen datos en la BD</h2>";
        } else { 
            echo "<h2>" . $titulo . "</h2>";


            //Obtener y mostrar los nombres de los campos (tambien llamadas cabeceras): 
            $campos = mysqli_fetch_fields($consulta);

            echo "<table class='consultaSQL'>"; 
            
            echo "<tr>";  
            
            foreach ($campos as $campo) { 
                /*
                    $campo es un objeto.
                    Por tanto requiere usar la propiedad name para ser capaz de traducirlo a una cadena.
                */
                echo "<th>$campo->name</th>"; 
            } 
            
            echo "</tr>"; 
            
            
            while ($fila = mysqli_fetch_assoc($consulta)) { 
                echo "<tr>"; 
                
                //$fila es un array asociativo que contiene una fila, por tanto todos los campos de esa fila
                //no contiene solamente un único indice con un único valor
                foreach ($fila as $valor) { 
                    echo "<td>";
                    
                    //strpos se usa para reconocer si el campo contiene una imagen:
                    if (strpos($valor, ".")) {
                        echo "<img alt='$valor' src='" . $ubicacion . $valor . "'>";
                    } else {
                        echo "$valor";
                    }

                    echo "</td>";
                } 
                
                echo "</tr>"; 
            } 
            
            echo "</table>"; 
        } 
    }    


    /* 
        Necesito capturar la clave primaria entera 
        para que la función se adapte a cualquier tabla 
        para así poder reutilizarla en cualquier formulario para modificar o borrar 
        sin riesgo a afectar a más de un recurso.

        Podria crear tambien otro parámetro para definir en que fichero se controla el borrado y modificado pero eso ya es demasiado,
        borrar.php y modificar.php son buenos nombres.
    */
    function selectConBotones ($conector, $sentenciaConsulta, $titulo = "Propiedades", $ubicacion = "imagenes/", $tamannioClavePrimaria = 1, $tabla = "PROPIEDADES") {
        $consulta = mysqli_query($conector, $sentenciaConsulta); 
        
        $numeroFilas = mysqli_num_rows($consulta); 
        
        if ($numeroFilas == 0) { 
            echo "<h2 id='SinResultados'>No existen datos en la BD</h2>";
        } else { 
            echo "<h2>" . $titulo . "</h2>";


            //Obtener y mostrar los nombres de los campos (tambien llamadas cabeceras): 
            $campos = mysqli_fetch_fields($consulta);

            echo "<table class='consultaSQL'>"; 


                //Zona de cabeceras:
                echo "<tr>";  
                
                //Es necesario usar $numRegistro aun consiguiendo capturar la clave primaria:
                $numRegistro = 1;            

                foreach ($campos as $campo) { 
                    /*
                        $campo es un objeto.
                        Por tanto requiere usar la propiedad name para ser capaz de traducirlo a una cadena.
                    */
                    $valor = $campo->name;
                    
                    echo "<th>$valor</th>";
                } 

                echo "<th>Borrar</th>";
                echo "<th style='background-color:black;'>Modificar</th>";
                
                echo "</tr>"; 
                    

                //Zona de cuerpo:
                while ($fila = mysqli_fetch_assoc($consulta)) { 
                    $contador = 0;
                    
                    echo "<tr>"; 
                    
                    //$fila es un array asociativo que contiene una fila, por tanto todos los campos de esa fila
                    //no contiene solamente un único indice con un único valor
                    foreach ($fila as $valor) { 
                        echo "<td>";
                        
                        //strpos se usa para averiguar si el campo contiene una imagen:
                        if (strpos($valor, ".")) {
                            echo "<img alt='$valor' src='" . $ubicacion . $valor . "'>";
                        } else {
                            echo $valor;
                        }

                        echo "</td>";

                        if ($contador < $tamannioClavePrimaria) {
                            $_SESSION["clavePrimaria"][$numRegistro][] = $valor;

                            $contador += 1;
                        }
                    } 
                    

                    echo "<td>";
                    echo "<form action='borrar.php' method='POST'>";
                    echo "<button type='submit' name='borrar' value='$numRegistro'>Borrar</button>";
                    echo "<input type='hidden' name='vengoDe$tabla'>";
                    echo "</form>";
                    echo "</td>";
                    
                    echo "<td>";
                    echo "<form action='modificar.php' method='POST'>";
                    echo "<button type='submit' name='modificar' value='$numRegistro'>Modificar</button>";
                    echo "<input type='hidden' name='vengoDe$tabla'>";
                    echo "</form>";
                    echo "</td>";

                    echo "</tr>"; 


                    $numRegistro++;
                } 
            

            echo "</table>"; 
        } 
    }
    
    
    function insertarEnBD ($conector, $tabla, $valores) {
        $valores = arrayACadena($valores, "', '");
        
        $insercion = "INSERT INTO $tabla value ('$valores')";

        try {
            $resultado = mysqli_query($conector, $insercion);

            if ($resultado) {
                echo "<h2>Registro insertado con éxito</h2>";    
            }
        } catch (Exception $error) {
            echo "<h2>" . $error -> getMessage() . "</h2>";
            /*
                Para diagnostico:
                echo "<br>";
                echo "<p>La sentencia recibida ha sido $insercion </p>";
            */
        }
    }
    
    
    function hacerCondicionesSentencia ($campos, $valores) {
        $salida = "";
        $i = 0;

        foreach ($campos as $valor) { 
            if ($i != count($campos) - 1) {
                if (is_numeric($valores[$i])) {
                    $salida .= $valor . " = " . $valores[$i] . " AND ";
                } else {
                    $salida .= $valor . " = '" . $valores[$i] . "' AND ";
                }
            } else {
                if (is_numeric($valores[$i])) {
                    $salida .= $valor . " = " . $valores[$i];
                } else {
                    $salida .= $valor . " = '" . $valores[$i] . "'";   
                }
            }

            $i++;
        }

        return $salida;
    }


    function parametrosAModificar ($datos, $valoresAComprobar) {
        $salida = "";
        $i = 0;

        foreach ($datos as $nombre => $valor) {
            foreach ($valoresAComprobar as $campo) {
                if ($nombre == $campo) {
                    
                    if ($i != count($datos) - 1) {
                        
                        if (is_numeric($valor)) {
                            $salida .= $nombre . " = " . $valor . " , ";
                        } else {
                            $salida .= $nombre . " = '" . $valor . "' , ";
                        }
                        
                    } else {
                        if (is_numeric($valor)) {
                            $salida .= $nombre . " = " . $valor;
                        } else {
                            $salida .= $nombre . " = '" . $valor . "'";   
                        }
                    }
        
                    $i++;

                }
            }    
        }

        return $salida;
    }

    
    function arrayACadena ($array, $separador) {
        $final = count($array) - 1;
        $cadena = "";

        foreach ($array as $key => $value) {
            if ($key != $final) {
                $cadena .= $value . $separador;
            } else {
                $cadena .= $value;
            }
        }

        return $cadena;
    }


    /*
        Necesito esta función porque si no, 
        hay problemas en el borrado y modificado que proporciona la función de selectConBotones(),
        estos problemas son que esta función deja de funcionar si uso JOINS o alias,
        ambos para mejorar la estética.
    */
    function capturaCabeceras ($conector, $tabla = "PROPIEDADES", $tamannioClavePrimaria = 2) {
        $contador = 0;
        $sentenciaConsulta = "SELECT * FROM $tabla limit 1;";
        
        $consulta = mysqli_query($conector, $sentenciaConsulta); 
        $campos = mysqli_fetch_fields($consulta);

        foreach ($campos as $campo) { 
            /*
                $campo es un objeto.
                Por tanto requiere usar la propiedad name para ser capaz de traducirlo a una cadena.
            */
            $valor = $campo->name;
                
            if ($contador < $tamannioClavePrimaria) {
                $camposClavePrimaria[] = $valor;

                $contador += 1;
            } else {
                $camposNormales[] = $valor;
            }
        }

        //Capturo los 2 tipos de campos porque los necesito para los placeholder, es una cosa que quiero hacer si me da tiempo.
        $salida = array($camposClavePrimaria, $camposNormales);

        return $salida;
    }
?>