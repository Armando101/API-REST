<?php

	$data = [
	    'username' => 'tecadmin',
	    'password' => '012345678'
	];
	 
	$payload = json_encode($data);
	
	/* Se inicia la conexión al servidor */
	$ch = curl_init('https://api.example.com/api/1.0/user/login');
	
	/* Decimps que retorne el resultado de la conexión al servidor */
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	/* No queremos ver los encabezados */
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);

	/* Indicamos que la petición será con el verbo POST */
	curl_setopt($ch, CURLOPT_POST, true);

	/* Dentro del POST viaja la información de $playload */
	curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
	
	/* Configuramos los encabezados para que el servidor sepa lo que le enviamos
	/* Indicamos que vamos a enviar un JSON */
	/* Indicamos la longitud de la cadena que enviamos */
	curl_setopt($ch, CURLOPT_HTTPHEADER,
		[ 
		    'Content-Type: application/json',
		    'Content-Length: ' . strlen($payload)
		]
	);
 
 	/* Realizamos la ejecución de la petición y guardamos el resultado en $result */
	$result = curl_exec($ch);

	/* Cerramos la conexión */
	curl_close($ch);

?>