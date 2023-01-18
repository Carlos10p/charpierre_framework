<?php
/*
*	Clase para encriptar/desencriptar mensajes
*	Utiliza como interfaz openssl: https://www.php.net/manual/es/book.openssl.php
*	Notas:
*		+ Si se instancia el objeto
*			- Al instanciarse, el constructor busca/genera llaves para usar
*				con las funciones encrypt y decrypt
*			- Podemos pasar una ruta a la carpeta donde se almacenan las llaves
*				como parametro del constructor, por default busca la carpeta "keys"
*
*		+ Si usamos los metodos estaticos
*			- Si no deseamos usar las llaves, podemos utilizar los metodos
*				estaticos: encryptString y decryptString
*			- Si usamos los metodos estaticos, podemos modificar el metodo
*				de encriptacion atraves del setter setCypherMethod
*/


class Cypher
{
	// Opciones de configuracion al crear una nueva llave openssl_pkey_new
	private $config;
	// Ubicacion de mis llaves
	private $pathKeys;
	private $privateKeyPath;
	private $publicKeyPath;
	// Recurso/string con la llave Privada o Publica
	private $privateKey;
	private $publicKey;
	// Metodo de cifrado personalizado
	// 	AES muy seguro
	//		AES-128-CBC
	// 	Blowfish sin iv (menos seguro)
	// 		BF-ECB
	// 	Blowfish con iv (mas seguro)
	// 		BF-CBC
	private static $cypherMethod = 'BF-CBC';

	// Tamaño en bits de la llave 
	private $keyBits;

	// Tamaño del bloque (encriptar)
	private $ENCRYPT_BLOCK_SIZE;

	// Tamaño del bloque (desencriptar)
	private $DECRYPT_BLOCK_SIZE;

	// Separador del vector de inicializacion
	private static $SEPARATOR = ':::||:::';

	/********************************************************************************************
		FUNCION: __construct
		OBJETIVO: Inicializa las propiedades del objeto. 
			Genera llaves privada y publica si no existen.
		PARAMETROS:
			$path.- Ruta de la ubicacion de la carpeta para nuestras llaves
	********************************************************************************************/
	function __construct($path='')
	{
		// Tamaños de nuestro bloque
		// Para una llave de 2048bits = 256 bytes, puede almacenar 245bytes de datos (11 de padding)
		// Formula espacio Data a encriptar = ( (keyBits / 8 bits)*(1byte) )  - 11bytes
		$this->keyBits = 2048;
		$this->ENCRYPT_BLOCK_SIZE = ceil($this->keyBits / 8) - 11;
		$this->DECRYPT_BLOCK_SIZE = ceil($this->keyBits / 8);

		// Opciones de configuracion
		$this->config = array(
			// Solo se usa esta opcion si se genera un certificado: openssl_csr_new()
		    	// "digest_alg" => "sha512",
		    // Especifica cuantos bits deberian usarse para generar una clave privada
		    "private_key_bits" => $this->keyBits,
		    "private_key_type" => OPENSSL_KEYTYPE_RSA
		);

		// Si se especifica una ruta para las llaves
		if (!empty($path)) {
			$this->pathKeys = $path;
		} else {
			$this->pathKeys = __DIR__.DIRECTORY_SEPARATOR.'keys'.DIRECTORY_SEPARATOR;
		}
		
		$this->privateKeyPath = $this->pathKeys.'p00';
		$this->publicKeyPath = $this->pathKeys.'p00.pub';

		// Carga las llaves para usarlas en esta Clase
		$this->loadKeys();

	}

	/********************************************************************************************
		FUNCION: setCypherMethod
		OBJETIVO: Setter de variable estatica $cypherMethod
		PARAMETROS:
	********************************************************************************************/
	public static function setCypherMethod($method)
	{
		self::$cypherMethod = $method;
	}

	/********************************************************************************************
		FUNCION: getCypherMethod
		OBJETIVO: Getter de variable estatica $cypherMethod
		PARAMETROS:
		REGRESA:
			Variable estatica $cypherMethod
	********************************************************************************************/
	public static function getCypherMethod()
	{
		return self::$cypherMethod;
	}

	/********************************************************************************************
		FUNCION: createKeys
		OBJETIVO: Busca si existen llaves de encriptacion. Si no existen las crea.
			Requiere que exista el directorio especificado en la variable $pathKeys 
			y que el script tenga permisos de escritura.
		PARAMETROS:
		REGRESA:
			TRUE en caso de exito | FALSE en caso de fallo
	********************************************************************************************/
	private function createKeys()
	{
		if (!file_exists($this->privateKeyPath))
		{
			// Create the private and public key
			$res = openssl_pkey_new($this->config);

			// Creamos el archivo de la llave privada
			if (openssl_pkey_export_to_file($res, $this->privateKeyPath)) {
				// Extract the public key from $res to $pubKey
				$pubKey = openssl_pkey_get_details($res);
				$pubKey = $pubKey['key'];
				$fbytes = file_put_contents($this->publicKeyPath, $pubKey);

				openssl_pkey_free($res);
				return ($fbytes !== FALSE);
			}
			openssl_pkey_free($res);
		}
		
		return false;

	}

	/********************************************************************************************
		FUNCION: loadKeys
		OBJETIVO: Carga las llaves privada y publica como propiedades de esta clase
		PARAMETROS:
	********************************************************************************************/
	private function loadKeys()
	{
		try {
			$publicLoaded = $this->loadPublicKey();
			$privateLoaded = $this->loadPrivateKey();
			// Si falla al cargar las llaves
			if (!$publicLoaded || !$privateLoaded) {
				$res = $this->createKeys();
				if ($res) {
					throw new Exception('Par de llaves creado exitosamente!');
				} else {
					throw new Exception('Error al cargar/crear llaves');
				}
			}
		} catch (Exception $e) {
			throw new Exception('Cypher Err:' . $e->getMessage());
		}
	}

	/********************************************************************************************
		FUNCION: loadPublicKey
		OBJETIVO: Carga las llave publica a la propiedad $publicKey
		PARAMETROS:
		REGRESA:
			TRUE en caso de exito | FALSE en caso de fallo
	********************************************************************************************/
	private function loadPublicKey()
	{
		$sFile = file_get_contents($this->publicKeyPath);
		if ($sFile !== FALSE) {
			$res = openssl_pkey_get_public($sFile);
			$keyData = openssl_pkey_get_details($res);

			$this->publicKey = $keyData['key'];
			return true;
		}

		return false;
	}

	/********************************************************************************************
		FUNCION: loadPrivateKey
		OBJETIVO: Carga las llave privada a la propiedad $privateKey
		PARAMETROS:
		REGRESA:
			TRUE en caso de exito | FALSE en caso de fallo
	********************************************************************************************/
	private function loadPrivateKey()
	{
		$sFile = file_get_contents($this->privateKeyPath);
		if ($sFile !== FALSE) {
			$res = openssl_pkey_get_private($sFile);
			//$keyData = openssl_pkey_get_details($res);

			$this->privateKey = $res;
			return true;
		}

		return false;

	}

	/********************************************************************************************
		FUNCION: encrypt
		OBJETIVO: Funcion de encriptado. Requiere que existan llaves de encriptacion.
			Se encripta con la llave publica para que pueda ser solamente
			desencriptado con la llave privada.
		PARAMETROS:
			$sData.- String de datos a encriptar
			$key.- (opcional) Llave publica proporcionada por usuario (programador)
		REGRESA:
			Cadena binaria encriptada
	********************************************************************************************/
	public function encrypt($sData, $key='')
	{
		$res = '';
		$position = 0;
		// Si es numero, conviertelo a string
		if (is_numeric($sData)) {
			$sData = (string) $sData;
		}

		// Por default: Usa llave publica
		if (empty($key)) {
			$key = $this->publicKey;
		}

		while ($dataChunk = mb_strcut($sData, $position, $this->ENCRYPT_BLOCK_SIZE)) {
			$encrypted = '';
			// Encripta usando la llave publica
			// para que solo se pueda desencriptar usando la llave privada
			openssl_public_encrypt($dataChunk, $encrypted, $key);

			$position += $this->ENCRYPT_BLOCK_SIZE;
			$res .= $encrypted;
		}

		return $res;

	}

	/********************************************************************************************
		FUNCION: decrypt
		OBJETIVO: Funcion de desencriptado. Requiere que existan llaves de encriptacion.
			Se desencripta con la llave privada.
		PARAMETROS:
			$sData.- String de datos a desencriptar
			$key.- (opcional) Llave privada proporcionada por usuario (programador)
		REGRESA:
			Cadena desencriptada
	********************************************************************************************/
	public function decrypt($sData, $key='')
	{
		$res = '';
		$position = 0;

		// Por default: Usa llave privada
		if (empty($key)) {
			$key = $this->privateKey;
		}

		while ($dataChunk = mb_strcut($sData, $position, $this->DECRYPT_BLOCK_SIZE)) {
			$decrypted = '';
			// Desencripta usando la llave privada
			openssl_private_decrypt($dataChunk, $decrypted, $key);

			$position += $this->DECRYPT_BLOCK_SIZE;
			$res .= $decrypted;

		}

		return $res;
	}

	/********************************************************************************************
		FUNCION: encryptString
		OBJETIVO: Funcion de encriptado. Encripta un mensaje en base a la llave proporcionada.
			La funcion agrega el vector de inicializacion (IV) al principio del texto
			cifrado. El IV debe ser el mismo que se usa para encriptar y desencriptar.
			El IV se genera de forma aleatoria para mayor seguridad.
		PARAMETROS:
			$msg.- String de datos a encriptar
			$key.- Llave/password proporcionado por el usuario (programador)
		REGRESA:
			String codificado en base64
	********************************************************************************************/
	public static function encryptString($msg, $key) {
		$longitud_iv = openssl_cipher_iv_length(self::$cypherMethod);
		$isSecureEnough = FALSE;
		$iv = openssl_random_pseudo_bytes($longitud_iv, $isSecureEnough);
		$cypherText = '';

		if (!$isSecureEnough) {
			throw new Exception('Cypher: El algoritmo no es criptograficamente seguro');
		}

		$cypherText = openssl_encrypt($msg, self::$cypherMethod, $key, OPENSSL_RAW_DATA, $iv);
		$cypherText = base64_encode($cypherText);
		$cypherText = base64_encode($iv) . self::$SEPARATOR . $cypherText;
		
		return base64_encode($cypherText);
	}

	/********************************************************************************************
		FUNCION: decryptString
		OBJETIVO: Funcion de desencriptado. Desencripta un mensaje en base a 
			la llave proporcionada.
		PARAMETROS:
			$msg.- String de datos a desencriptar
			$key.- Llave/password proporcionado por el usuario (programador)
		REGRESA:
			String desencriptado
	********************************************************************************************/
	public static function decryptString($msg, $key) {
		$msg = base64_decode($msg);
		/*
			// Metodo alterno para obtener IV del mensaje cifrado
			$longitud_iv = openssl_cipher_iv_length($this->cypherMethod);
			$iv = mb_strcut($msg, 0, $longitud_iv);
			$msg = mb_strcut($msg, $longitud_iv, NULL);
		*/
		$msg = explode(self::$SEPARATOR, $msg);
		$iv = !empty($msg[0]) ? base64_decode($msg[0]) : '';
		$msg = !empty($msg[1]) ? base64_decode($msg[1]) : '';

	    $plainText = openssl_decrypt($msg, self::$cypherMethod, $key, OPENSSL_RAW_DATA, $iv);
		
		return $plainText;
	}

	
}
