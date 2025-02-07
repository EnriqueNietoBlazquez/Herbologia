<?php
    /*  
        Include solo genera una advertencia si el archivo no se encuentra
        Require genera un error fatal y detiene la ejecución

        El uso de ambas (desde el fichero receptor) es:
            <?php
                include 'manejoDeFormularios.lib.php'; 
                require 'manejoDeFormularios.lib.php';
            ?>
    */
?>



<?php
    function guardarImagenSubida ($filesNameInputFichero, $destino = "/IAW/Proyecto/Entrega/imagenes/") {
        $destino = $_SERVER['DOCUMENT_ROOT'] . $destino;
        $rutaEnDestino = $destino . $filesNameInputFichero['name'];

        if (file_exists($rutaEnDestino)) {
            /*
                Si el fichero existe y además es el mismo, no se hará nada.

                Sin embargo el sistema será capaz de reconocer si el fichero es el mismo o solamente tienen el mismo nombre,
                si tienen el mismo nombre pero son distintos, el sistema reaccionará adecuadamente.
            */
            if (! compararImagenes($rutaEnDestino, $_FILES['Aspecto']['tmp_name'])) {
                move_uploaded_file($filesNameInputFichero['tmp_name'], $rutaEnDestino);
            }
        } else {
            move_uploaded_file($filesNameInputFichero['tmp_name'], $rutaEnDestino);
        }

    }    


    function traducirArrayIndexadoAVariables ($array) {
        foreach ($array as $key => $value) {
            global $$key;
            $$key = $value;
        }
    }


    function compararImagenes($rutaImagen1, $rutaImagen2) {
        $hashImagen1 = md5_file($rutaImagen1);
        $hashImagen2 = md5_file($rutaImagen2);
        
        return $hashImagen1 == $hashImagen2;
    }


    function comprobarTexto ($presuntoNulo) {
		$salida = false;
        
        /*
            La segunda condición no es necesaria, 
            pero la pongo por si acaso, para detectar por ejemplo 
            errores en el código en vez de solamente en el usuario,
            ya que hay errores que pueden hacer que se cumpla la condición
        */
		if ($presuntoNulo == "" || $presuntoNulo == " ") {
			echo "<p class='error'>Error, no se admite valor nulo</p>";
		} elseif (is_numeric($presuntoNulo)) {
            echo "<p class='error'>Error, no se admiten números</p>";	
        } else {
            $salida = true;
        }
		
		return $salida;
    }


    function recorrerArrayBuscandoValor($valorBuscado, $array) {
        $resultado = false;
        
        foreach ($array as $valor) {
            if ($valor == $valorBuscado) {
                $resultado = true;
            }
        }

        return $resultado;
    }


    function comprobarNumeroRango ($presuntoNumero) {
        $salida = false;

        /*
            La segunda parte de la primera condición no es necesaria, 
            pero la pongo por si acaso, para detectar por ejemplo 
            errores en el código en vez de solamente en el usuario,
            ya que hay errores que pueden hacer que se cumpla.
        */
		if ($presuntoNumero == "" || $presuntoNumero == " ") {
			echo "<p class='error'>Error, no se admite valor nulo</p>";
		} elseif (! is_numeric($presuntoNumero)) {
            echo "<p class='error'>Error, solo se admiten números</p>";

            //Las siguientes 2 condiciones podria hacerlas en HTML con min y max.
        } elseif ($presuntoNumero <= 0) {
            echo "<p class='error'>Error, el mínimo es 1</p>";
        } elseif ($presuntoNumero >= 11) {
            echo "<p class='error'>Error, el máximo es 10</p>";
        } else {
            $salida = true;
        }
		
		return $salida;
    }


    function comprobarNumero ($presuntoNumero) {
        $salida = false;

        /*
            La segunda parte de la primera condición no es necesaria, 
            pero la pongo por si acaso, para detectar por ejemplo 
            errores en el código en vez de solamente en el usuario,
            ya que hay errores que pueden hacer que se cumpla.
        */
		if ($presuntoNumero == "" || $presuntoNumero == " ") {
			echo "<p class='error'>Error, no se admite valor nulo</p>";
		} elseif (! is_numeric($presuntoNumero)) {
            echo "<p class='error'>Error, solo se admiten números</p>";

            //Las siguientes 2 condiciones podria hacerlas en HTML con min y max.
        } elseif ($presuntoNumero <= 0) {
            echo "<p class='error'>Error, el mínimo es 1</p>";
        } else {
            $salida = true;
        }
		
		return $salida;
    }
?>