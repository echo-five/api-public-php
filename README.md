# Echo-Five API Public PHP

[![License](https://img.shields.io/github/license/echo-five/api-public-php?label=Licence&style=flat-square)](https://github.com/echo-five/api-public-php/blob/master/LICENSE)
![Size](https://img.shields.io/github/languages/code-size/echo-five/api-public-php?label=Size&style=flat-square)

This is a PHP library to communicate with the Echo-Five public API.  
Echo-Five public API is my personal API.

## Menu

- [Installation](#installation)
    * [How to install?](#how-to-install)
    * [How to update?](#how-to-update)
    * [How to remove?](#how-to-remove)
- [Get started](#get-started)
- [License](#license)

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

*This example is stored in the project and can be downloaded here: [example-get-started.php](https://github.com/echo-five/api-public-php/blob/master/examples/example-get-started.php)*

## License

[MIT](https://choosealicense.com/licenses/mit/)