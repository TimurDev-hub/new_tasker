<?php

require_once __DIR__ . '/../../../vendor/autoload.php';

$uri = $_SERVER['REQUEST_URI'];
$httpMethod = $_SERVER['REQUEST_METHOD'];

try {


} catch (\Throwable) {
	http_response_code(404);
	require_once __DIR__ . '/../../client/view/index.html';
}