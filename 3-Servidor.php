<?php 
	
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
			# code...
			break;
		
		case 'PUT':
			# code...
			break;
		
		case 'DELETE':
			# code...
			break;
		
		default:
			# code...
			break;
	}

?>