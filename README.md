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
- [License](#license)

## Requirements

- PHP 7.4 or higher with cURL and JSON extentions

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

This package can be unistalled via Composer:

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
When receiving a signed request, the API checks that the signature of the request sent with the data, is correct.  
If the signatures do not match, the request is not processed.  

The usage of signed queries is very simple!  
The only thing to do is to declare the secret key associated with the API key when you instantiate the library.  
Then all requests will be signed!

	// Initialize.
	$api = new EchoFiveApiPublic('api.matthieuroy.be', 'MY_API_KEY', 'MY_API_SECRET');

*This example is stored in the project and can be downloaded here: [get-started-signed-request.php](https://github.com/echo-five/api-public-php/blob/master/examples/get-started-signed-request.php)*

## License

[MIT](https://choosealicense.com/licenses/mit/)