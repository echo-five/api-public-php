# Echo-Five API Public PHP

[![License](https://img.shields.io/github/license/echo-five/api-public-php?label=Licence&style=flat-square)](https://github.com/echo-five/api-public-php/blob/master/LICENSE)
![Size](https://img.shields.io/github/languages/code-size/echo-five/api-public-php?label=Size&style=flat-square)

This is a PHP library to communicate with the Echo-Five public API.  
Echo-Five public API is my personal API.

## Menu

- [Requirements](#requirements)
- [Installation](#installation)
    * [How to install?](#how-to-install)
    * [How to update?](#how-to-update)
    * [How to remove?](#how-to-remove)
- [Get started](#get-started)
- [Features](#features)
    * [Simple request vs Signed request](#simple-request-vs-signed-request)
- [Available methods](#available-methods)
	* [Request](#request) 
	* [Get Request Response](#get-request-response)
- [License](#license)

## Requirements

- PHP 7.4 or higher with cURL and JSON extensions

## Installation

### How to install?

This package can be installed via Composer:

    composer require echo-five/api-public-php

### How to update?
  
Use the following command to update this package only:

	composer update echo-five/api-public-php

OR  
  
Use the following command to update all Composer packages (including this one):

	composer update

### How to remove?

This package can be uninstalled via Composer:

    composer remove echo-five/api-public-php

## Get started

Assuming the library has been installed via Composer, create a new blank PHP file and copy the code below:

	<?php

	// Load Composer autoloader.
	require 'vendor/autoload.php';
	
	// Use declaration.
	use EchoFiveApiPublic\EchoFiveApiPublic;

	// Initialize.
	$api = new EchoFiveApiPublic('api.matthieuroy.be', 'MY_API_KEY');

	// Execute a request.
	$api->request('post', '/api/v1/test/simple', [
	    'foo' => 'Bar',
	    'biz' => 'Buz',
	]);

	// Get the response.
	$apiResponse = $api->getRequestResponse();

	// Dump.
	var_dump($apiResponse);

*This example is stored in the project and can be downloaded here: [get-started-simple-request.php](https://github.com/echo-five/api-public-php/blob/master/examples/get-started-simple-request.php)*

## Features

### Simple request vs Signed request

The library allows two types of requests, simple requests (unsigned) and signed requests (signed).  

Signed requests ensure that the data that are sent to the API are not modified.  
Signed requests, although slightly slower, are therefore more secure than simple requests.  
When receiving a signed request, the API checks that the signature of the request, sent with the data, is correct.  
If the signatures do not match, the request is not processed.  

The usage of signed queries is very simple!  
The only thing to do is to declare the secret key associated with the API key when you instantiate the library.  
Then all requests will be signed!

	// Initialize.
	$api = new EchoFiveApiPublic('api.matthieuroy.be', 'MY_API_KEY', 'MY_API_SECRET');

*This example is stored in the project and can be downloaded here: [get-started-signed-request.php](https://github.com/echo-five/api-public-php/blob/master/examples/get-started-signed-request.php)*

## Available methods

### Request

This method allows making an API request.

> request(string $requestType, string $requestEndpoint, array <$requestParams>, string <$requestMode>)

- The `requestType` argument defines the type of the request.  
Allowed values are `GET` and `POST`.  
This argument is mandatory.

- The `requestEndpoint` argument defines the endpoint to hit.  
This is an URI, e.g.: /foo/bar  
This argument is mandatory.

- The `requestParams` argument defines the data to send to the endpoint.  
This is an array, key/value based, by default no data are sent to the endpoint.  
This argument is optional.

- The `requestMode` argument defines the mode of sending used with the type of the request.  
Allowed values are `FORM`, `HTTP` and `JSON` (which is the default mode).  
This argument is only supported with `POST`request type.
This argument is optional.

This method return the class itself and not the result of the request.  
The request result must be fetched using another method (`getRequestResponse`).  
For more convenient use, this method is "chainable".

Example:

	$requestResult = $api->request('post', '/foo/bar')->getRequestResponse();

### Get Request Response

This method allows to get the response of the request.  

> getRequestResponse(bool <$decode>)

- The `decode` argument defines if the request response must be json-decoded or not.  
The API replies in JSON format, so the response is a string.  
The `decode` argument allows to get a PHP object instead a string.  
The `decode` argument, is set to `true` by default.

Examples:

	$api->getRequestResponse()  | Return a PHP object. 
	$api->getRequestResponse(1) | Return a PHP object.
	$api->getRequestResponse(0) | Return a JSON string.  

## License

[MIT](https://choosealicense.com/licenses/mit/)
