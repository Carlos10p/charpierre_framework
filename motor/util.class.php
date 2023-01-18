<?php
class Util
{
    /**
     * CEPM 20180207 () : CLASE DE UTILERÍAS ESTÁTICA DE SIE, LA IDEA ES QUE LAS FUNCIONES DE GLOBALES SE PASEN A ESTA CLASE ESTÁTICA.
     */

    private function __construct() {}


    public static function strToHex($string)
    {
        $hex = '';
        for ($i=0; $i<strlen($string); $i++){
            $ord = ord($string[$i]);
            $hexCode = dechex($ord);
            $hex .= substr('0'.$hexCode, -2);
            }
            return strToUpper($hex);
        }

    public static function hexToStr($hex)
    {
        $string='';
        for ($i=0; $i < strlen($hex)-1; $i+=2){
            $string .= chr(hexdec($hex[$i].$hex[$i+1]));
        }
        return $string;
    }

    public static function rellenaCerosIZQ($a_dato,$a_noCeros)
    {
        return str_pad($a_dato,$a_noCeros,'0',STR_PAD_LEFT);
    }

    public static function procesaParametroNullSQL($param,$esString)
    {
        if(trim($param) == "")
        {
            $param = "null";
        }
        else
        {
            if($esString)
            {
                $param = "'".$param."'";
            }
        }

        return $param;
    }

    public static function isTieneValor($dato)
    {
        if(!isset($dato) || trim($dato)==='')
        {
            return false;
        }
        else
        {
            return true;
        }
    }

    public static function procesaArrayLineal(&$arrDato)
    {
        //luism 20190218 (44036):AT El while de la funcion bd_dameregistros($res) al hacer fetch retorna null para la ultima iteracion port tanto no se debe evaluar el ultimo intento
        //http://php.net/manual/es/mysqli-result.fetch-assoc.php
        if (is_array($arrDato))
        {
            foreach($arrDato as $key => $value)
            {
                if(mb_detect_encoding($value, 'UTF-8', true)===false)
                {
                    $arrDato[$key] = utf8_encode($value);
                }
                else
                {
                    $arrDato[$key] = ($value);
                }
            }
        }
    }

    public static function codificaString($cadena)
    {
        if(mb_detect_encoding($cadena, 'UTF-8', true)===false)
        {
            $cadena = utf8_encode($cadena);
        }

        return $cadena;
    }

    public static function decodificaString($cadena)
    {
        if(mb_detect_encoding($cadena)==='UTF-8')
        {
            $cadena = utf8_decode($cadena);
        }

        return $cadena;
    }

    public static function procesaFoto($a_foto,$anchoDestino,$altoDestino,$calidadDestino,$isReduceFoto = true)
    {
        $rawData =$a_foto;
        $filteredData = explode(',', $rawData);
        $unencoded = base64_decode($filteredData[1]);
        $randomName = rand(0, 99999);;
        $foto=$unencoded;

		if($isReduceFoto)
		{
			$foto = base64_encode(self::reduce_imagen($foto,$anchoDestino,$altoDestino,$calidadDestino));
		}
		else
		{
			$foto = base64_encode($foto);
		}

        return $foto;
    }


	public static function reduce_imagen($imagen, $anchoDestino , $altoDestino, $calidad)
	{
		$resultado = false;

		//Obtenemos el contenido de la imagen
		$original = imagecreatefromstring($imagen);

		if ($original != false)
		{

			$Nueva = imageCreatetrueColor($anchoDestino,$altoDestino);
			if ($Nueva != false)
			{

				$ancho = imagesx($original);
				$alto = imagesy($original);

				$imagenReducida = null;

				//Refuzco la imagen
				$res =  imagecopyresampled($Nueva,$original,0,0,0,0,$anchoDestino,$altoDestino,$ancho,$alto);
				if($res)
				{
					//Creo el JPG
					ob_start(); // Start capturing stdout.
					imageJPEG($Nueva,null,$calidad); // As though output to browser.
					$sBinaryThumbnail = ob_get_contents(); // the raw jpeg image data.
					ob_end_clean(); // Dump the stdout so it does not screw other output.

					$resultado = $sBinaryThumbnail;
				}
				else
				{
					$resultado = false;
				}

				//$e = error_get_last();
				//$e = $e['message'];
				//$resultado = $e;

				return $resultado;
			}
		}

		return $resultado;
	}

    /********************************************************************************************
        FUNCION: rutaPublica()
        OBJETIVO: Obten ruta publica del servidor
        PARAMETROS:
    ********************************************************************************************/
    public static function rutaPublica()
    {
        $protocol = !empty($_SERVER['HTTPS']) ? 'https' : 'http';
        $host = $_SERVER['HTTP_HOST'];
        $finalPath = $protocol . '://' . $host;
        $dirUri = dirname($_SERVER['REQUEST_URI']);
        if ($dirUri !== '/' && $dirUri !== '\\') {
            $finalPath .= $dirUri;
        }
        $finalPath .= '/';

        return $finalPath;
    }

    /********************************************************************************************
        FUNCION: rutaPublicaImagenes()
        OBJETIVO: Obten ruta publica del servidor para Imagenes
        PARAMETROS:
            $tipo.- Tipo de Imagen
                (PORTADA, CONTRAPORTADA, ARTICULO-PORTADA,
                 ARTICULO-CONTRAPORTADA, ARTICULO-IMAGENES)
    ********************************************************************************************/
    public static function rutaPublicaImagenes($tipo)
    {
        $rutaPublica = self::rutaPublica();
        $finalPath = '';

        switch ($tipo)
        {
            case 'PORTADA':
                $finalPath = $rutaPublica . $_SESSION['rutas']['portadas'];
                break;
            case 'CONTRAPORTADA':
                $finalPath = $rutaPublica . $_SESSION['rutas']['contraportadas'];
                break;
            case 'ARTICULO-PORTADA':
                $finalPath = $rutaPublica . $_SESSION['rutas']['articulos']['portadas'];
                break;
            case 'ARTICULO-CONTRAPORTADA':
                $finalPath = $rutaPublica . $_SESSION['rutas']['articulos']['contraportadas'];
                break;
            case 'ARTICULO-IMAGENES':
                $finalPath = $rutaPublica . $_SESSION['rutas']['articulos']['imagenes'];
                break;
            // SARR 20210304() : Se agregan finalPath de variables session secciones
            case 'SECCIONES-SOMOS':
                $finalPath = $rutaPublica . $_SESSION['rutas']['secciones']['imagenes'];
                break;
            case 'REFLEXIONES':
                $finalPath = $rutaPublica . $_SESSION['rutas']['reflexiones']['portadas'];
                break;
            default:
                break;
        }
        $finalPath = str_replace('\\', '/', $finalPath);

        return $finalPath;
    }

    // Función para escapar los caracteres especiales de un parámetro
    // Tomada de motor/funciones.php
    public static function escapaParametro($valor)
    {
        if(is_array($valor))
        return array_map(__METHOD__, $valor);

        if(!empty($valor) && is_string($valor))
        {
            return str_replace(array('\\', "\0", "\n", "\r", "'", '"', "\x1a"), array('\\\\', '\\0', '\\n', '\\r', "\\'", '\\"', '\\Z'), $valor);
        }
        return $valor;
    }

}
