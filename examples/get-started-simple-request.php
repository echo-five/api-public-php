<?php

// Load Composer autoloader.
require 'vendor/autoload.php';

// Use declaration.
use EchoFiveApiPublic\EchoFiveApiPublic;

// Initialize.
$api = new EchoFiveApiPublic('api.matthieuroy.be', 'MY_API_KEY');

// Do a request.
$api->request('post', '/api/v1/test/simple', [
    'foo' => 'Bar',
    'biz' => 'Buz',
]);

// Get the request response.
$requestResponse = $api->getRequestResponse();

// Dump.
var_dump($requestResponse);
