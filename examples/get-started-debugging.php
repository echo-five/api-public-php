<?php

// Load Composer autoloader.
require 'vendor/autoload.php';

// Use declaration.
use EchoFiveApiPublic\EchoFiveApiPublic;

// Initialize.
$api = new EchoFiveApiPublic('api.matthieuroy.be', 'MY_API_KEY');

// Start the debugging mode.
$api->debugStart();

// Do a request.
$api->request('post', '/api/v1/test/simple', [
    'foo' => 'Bar',
    'biz' => 'Buz',
]);

// Stop the debugging mode.
$api->debugStop();

// Get the request response.
$requestResponse = $api->getRequestResponse();

// Dumps.
var_dump($requestResponse);
var_dump($api->debugGet());
