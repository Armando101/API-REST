<?php 
	
	/*
		// Autenticación por HTTP
	// Tomamos las credenciales que manda el usuario
	$user = array_key_exists('PHP_AUTH_USER', $_SERVER) ? $_SERVER['PHP_AUTH_USER'] : '';
	$pwd = array_key_exists('PHP_AUTH_PW', $_SERVER) ? $_SERVER['PHP_AUTH_PW'] : '';

	// Es una mala práctica hardcodear los datos pero para fines didacticos haremos la autenticación de esta manera
	if ( $user !== 'Armando' || $pwd !== '1234') {
		die;
	}
	*/
/*
		// Autencicación por HMAC

	// Verificamos que la información enviada sea completa
	// Necesitamos: HASH, Estampa de tiempo y el id
	if (
		!array_key_exists('HTTP_X_HASH', $_SERVER) ||
		!array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) ||
		!array_key_exists('HTTP_X_UID', $_SERVER) 

	) {
		echo 'Error no Autorizado';
		die;
	}
	
	// Capturamos los datos
	list($hash, $uid, $timestamp) = [
		$_SERVER['HTTP_X_HASH'],
		$_SERVER['HTTP_X_UID'],
		$_SERVER['HTTP_X_TIMESTAMP']
	];

	// Tomamos la palabra secreta
	$secret = 'Sh!! No se lo cuentes a nadie';

	// Generamos el HASH
	$newHash = sha1($uid.$timestamp.$secret);

	// Si son diferentes los hash's no se autentica
	if ( $newHash !== $hash) {
		echo $newHash;
		echo "\n";
		echo $hash;
		echo "No coinciden los HASH";
		die;
	}

*/

		// Autenticación por Token

	// Verificamos que el usuario haya mandado el token
	if ( !array_key_exists('HTTP_X_TOKEN', $_SERVER) ) {
		die;
	}


	$url = 'http://localhost:8001';

	// Hago una petición vía curl para verificar si el token es o no válido
	// Inicializo la llamada
	$ch = curl_init($url);
	// Indico en el encabezado el token a validar
	// Definimos el encabezado 'X-Token' y le coloco el valor que envió el cliente
	curl_setopt(
		$ch, 
		CURLOPT_HTTPHEADER,
		[
			"X-Token: {$_SERVER['HTTP_X_TOKEN']}"
		]);

	// Esta opción nos permite obtener el resultado de lo que me devuelve el servidor de autenticación
	curl_setopt(
		$ch, 
		CURLOPT_RETURNTRANSFER,
		true
	);

	// Verificamos que el resultado que me mando el servidor sea true
	$ret = curl_exec($ch);
	if ( $ret !== 'true') {
		die;
	}

	/* Declaramos un arreglo con los recursos posibles */
	$allowedResourceTypes = [
		'books',
		'authors',
		'generes'
	];

	/* Obtengo el tipo de recurso que solicitó el cliente */
	$resourceType = $_GET['resource_type'];

	/* Verifico que el recurso del cliente se encuentre entre los recursos disponibles */
	if ( !in_array($resourceType, $allowedResourceTypes) ) {
		die;
	}

	/* Defino los recursos */
	/* Idealmete estos estarían en una base de datos */

	$books = [
		1 => [
			'titulo' => 'Lo que el viento se llevo',
			'id_autor' => 2, 
			'id_genero' => 2, 
		],
		2 => [
			'titulo' => 'La Iliada',
			'id_autor' => 1, 
			'id_genero' => 1,
		],
		3 => [
			'titulo' => 'La Odisea',
			'id_autor' => 1, 
			'id_genero' => 1, 
		]
	];

	/* Indicamos al usuario que vamos a enviar un JSON */
	/* Esto no es necesario, sólo es cortesía para el usuario */
	header( 'Content-Type: application/JSON');

	// Levantamos el id del recurso buscado
	// Esto para pedir un recurso en particular
	$resourceId = array_key_exists('resource_id', $_GET) ? $_GET['resource_id'] : '';


	/* Vemos que tipo de verbo me envió el cliente */
	switch ( strtoupper($_SERVER['REQUEST_METHOD'])) {
		case 'GET':

			// Verifico si el usuario me mandó o no un id
			if(empty($resourceId)) {
				/* Devolvemos la colección de libros */
				echo json_encode( $books );				
			} else {
				if( array_key_exists($resourceId, $books)) {
					echo json_encode($books[$resourceId]);
				}
			}

			break;
		
		case 'POST':
			// Recogemos los datos que me envía el cliente
			$json = file_get_contents('php://input');

			// Añadimos lo que el usuario me envió a mi DB
			// Colocamos true para que lo coloque en forma de Array
			$books[] = json_decode($json, true);

			// Por buenas prácticas devolvemos el ID del arreglo que se envió
			// -1 porque los arrays comienzan en 0
			echo array_keys( $books )[ count($books) - 1];
			break;
		
		case 'PUT':
			// Validamos que el recurso buscado exista
			if ( !empty($resourceId) && array_key_exists($resourceId, $books)) {
				// Tomamos la entrada cruda
				$json = file_get_contents('php://input');
				
				$books[ $resourceId ] = json_decode($json, true);

				// Retornamos la colección modificada en formato JSON
				echo json_encode( $books );
			}

			break;
		
		case 'DELETE':
			// Verifico que exista el id que me envió el usuario
 			if(!empty($resourceId) && array_key_exists($resourceId, $books)) {
 				// Eliminamos el recurso
 				unset( $books[ $resourceId ]);
			}

			echo json_encode($books);

			break;
		
		default:
			# code...
			break;
	}

?>