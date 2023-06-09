<?php

// ===========================================================================
// Autenticacion con HTTP
/*
$user = array_key_exists( 'PHP_AUTH_USER', $_SERVER ) 	? $_SERVER['PHP_AUTH_USER'] : '';
$pwd  = array_key_exists( 'PHP_AUTH_PW', $_SERVER ) 	? $_SERVER['PHP_AUTH_PW'] : '';

if ( $user !== 'nico' || $pwd !== '1234') {
	die;
}
Fin Autenticacion con HTTP
==============================================================================
*/



// ===========================================================================
// Autenticacion con HMAC // Codigo de autorizacion basado en hash de mensajes
/* 
if ( 
	!array_key_exists('HTTP_X_HASH', $_SERVER) || 
	!array_key_exists('HTTP_X_TIMESTAMP', $_SERVER) || 
	!array_key_exists('HTTP_X_UID', $_SERVER)
 ) {
	die();
}

list( $hash, $uid, $timestamp ) = [
	$_SERVER['HTTP_X_HASH'],
	$_SERVER['HTTP_X_UID'],
	$_SERVER['HTTP_X_TIMESTAMP']
];

$secret = 'Sh! no se lo cuentes a nadie';

$newHash = sha1($uid.$timestamp.$secret);

if ( $newHash !== $hash ) {
	die();
}
Fin Autenticacion con HMAC // Codigo de autorizacion basado en hash de mensajes
===========================================================================
*/




// ========================================================================
// Autenticación por Token
/*
if ( !array_key_exists( 'HTTP_X_TOKEN', $_SERVER ) ) {
	die('No hay autenticacion');
}

$url = 'http://localhost:8001';

$ch = curl_init( $url );
curl_setopt(
	$ch,
	CURLOPT_HTTPHEADER,
	[
		"X-Token: {$_SERVER['HTTP_X_TOKEN']}" 
	]
);
curl_setopt(
	$ch,
	CURLOPT_RETURNTRANSFER,
	true
);

$ret = curl_exec( $ch );

if ( $ret !== 'true' ) {
	die('Fallo la autenticacion');
}
*/

// Definimos los recursos disponibles
$allowedResourceTypes = [
	'books',
	'authors',
	'genres',
];

// Validamos que el recurso este disponible
$resourceType = $_GET['resource_type'];

if ( !in_array( $resourceType, $allowedResourceTypes) ) {
	http_response_code(400);
	die;
}

// Defino los recursos
$books = [
	1 => [
		'titulo' => 'Lo que el viento se llevo',
		'id_autor' => 1,
		'id_genero' => 1,
	],
	2 => [
		'titulo' => 'La Iliada',
		'id_autor' => 2,
		'id_genero' => 2,
	],
	3 => [
		'titulo' => 'La Odisea',
		'id_autor' => 3,
		'id_genero' => 2,
	]
];

// Avisar al cliente que respondemos json
header( 'Content-Type: application/json' );

// Levantamos el id del recurso buscado
$resourceId = array_key_exists( 'resource_id', $_GET ) ? $_GET['resource_id'] : '';

// Generamos la respuesta asumiendo que el pedido es correcto
switch ( strtoupper($_SERVER['REQUEST_METHOD']) ) {
	case 'GET':
		if ( empty( $resourceId ) ) {
			echo json_encode( $books );
		} else {
			if ( array_key_exists( $resourceId, $books ) ) {
				echo json_encode( $books[ $resourceId ] );
			} else {
				http_response_code(404);
			}
		}
		break;
	case 'POST':
		$json = file_get_contents('php://input');
		
		$books[] = json_decode( $json, true ); // Forma de arreglo

		//echo array_keys( $books )[ count($books) - 1 ];
		echo json_encode( $books );
		break;
	case 'PUT':
		// Validamos que el recurso buscado exista
		if ( !empty($resourceId) && array_key_exists( $resourceId, $books )) {
			$json = file_get_contents('php://input');

			// Transformamos el json recibido a un nuevo elemento
			$books[ $resourceId ] = json_decode( $json, true ); // Forma de arreglo
			
			// Retornamos la coleccion modificada en formato json
			echo json_encode( $books );
		}
		break;
	case 'DELETE':
		// Validar que el recurso exista
		if ( !empty($resourceId) && array_key_exists( $resourceId, $books )) {
			// Eliminamos el recurso
			unset( $books[ $resourceId ] );
		}

		echo json_encode( $books );
		break;
}