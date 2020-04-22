<?php 
	$json = file_get_contents('https://xkcd.com/info.0.json').PHP_EOL;
	/* Decodifica el json como un array */
	/* Si omito el true lo decodifica como objeto */
	$data = json_decode( $json, true); 
	echo $data['img'].PHP_EOL;
?>