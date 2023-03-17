<?php

// Definimos los recursos disponibles
$allowedResourceTypes = [
	'books',
	'authors',
	'genres',
];

// Validamos que el recurso este disponible
$resourceType = $_GET['resource_type'];

if ( !in_array( $resourceType, $allowedResourceTypes) ) {
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

// Generamos la respuesta asumiendo que el pedido es correcto
switch ( strtoupper($_SERVER['REQUEST_METHOD']) ) {
	case 'GET':
		echo json_encode( $books );
		break;
	case 'POST':
		break;
	case 'PUT':
		break;
	case 'DELETE':
		break;
}