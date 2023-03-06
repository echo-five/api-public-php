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
    * [Debugging](#debugging)
- [Available methods](#available-methods)
	* [Request](#request) 
	* [Get Request Response](#get-request-response)
	* [Get Request Response Status](#get-request-response-status)
	* [Get Request Response Data](#get-request-response-data)
	* [Get Request Response Messages](#get-request-response-messages)
	* [Get Request Info](#get-request-info)
    * [Debug Start](#debug-start)
    * [Debug Stop](#debug-stop)
    * [Debug Get](#debug-get)
    * [Debug Reset](#debug-reset)
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
	
	// Do a request.
	$api->request('post', '/api/v1/test/simple', [
	    'foo' => 'Bar',
	    'biz' => 'Buz',
	]);
	
	// Get the request response.
	$requestResponse = $api->getRequestResponse();
	
	// Dump.
	var_dump($requestResponse);

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

### Debugging

Troubleshooting the use of an API is not always easy.  
The library includes a debugging mode to understand better how requests are made.

The debugging mode allows:  

- To know the time consumption of the requests (in seconds).
- To know the execution number of the requests.
- To know the execution order of the requests.

The usage of the debugging mode is very simple!  
Just start from a specific break point and stop to another.  

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

*See [Available methods](#available-methods) section for more debuging options.*  
*This example is stored in the project and can be downloaded here: [get-started-debugging.php](https://github.com/echo-five/api-public-php/blob/master/examples/get-started-debugging.php)*

Here an example of the debugging information: 

	// Result:
	Array
	(
	    [requests] => Array
	        (
	            [time] => 0.218735
	            [count] => 1
	            [trace] => Array
	                (
	                    [2023-01-01T13:57:09.603425+01:00] => Start debug.
	                    [2023-01-01T13:57:09.822565+01:00] => Request | https://api.matthieuroy.be/api/v1/test/simple
	                    [2023-01-01T13:57:09.822592+01:00] => Stop debug.
	                )
	        )
	)

## Available methods

### Request

This method allows making an API request.

> request(string $requestType, string $requestEndpoint, array <$requestParams>, string <$requestMode>)

- The `requestType` argument defines the type of the request.  
Allowed values are `GET` and `POST`.  
This argument is mandatory.

- The `requestEndpoint` argument defines the endpoint to hit.  
This is an URI, e.g.: /api/v1/test/simple  
This argument is mandatory.

- The `requestParams` argument defines the data to send to the endpoint.  
This is an array, key/value based, by default no data are sent to the endpoint.  
This argument is optional.

- The `requestMode` argument defines the mode of sending used with the type of the request.  
Allowed values are `FORM`, `HTTP` and `JSON` (which is the default mode).  
This argument is only supported with `POST`request type.
This argument is optional.

This method return the class instance itself and not the result of the request.  
The request result must be fetched using another method (`getRequestResponse`).  
For more convenient use, this method is "chainable".

Example:

	// Do a request and get the request response.
	$requestResponse = $api->request('post', '/api/v1/test/simple')->getRequestResponse();

### Get Request Response

This method allows to get the response of the request.  

> getRequestResponse(bool <$decode>)

- The `decode` argument defines if the request response must be json-decoded or not.  
The API replies in JSON format, so the response is a string.  
The `decode` argument allows to get a PHP object instead a JSON string.  
The `decode` argument, is set to `true` by default.

Usage examples:

	$requestResponse = $api->getRequestResponse();  // Return a PHP object. 
	$requestResponse = $api->getRequestResponse(1); // Return a PHP object.
	$requestResponse = $api->getRequestResponse(0); // Return a JSON string. 

This method return a JSON string or a PHP object, depending on the passed argument.  
The request response is always a full API response.  
Here an example: 

	// Request:
	$api->request('post', '/api/v1/test/simple', [
	    'foo' => 'Bar',
	    'biz' => 'Buz',
	]);

	// Response:
	stdClass Object
	(
	    [status] => 200
	    [data] => stdClass Object
	        (
	            [foo] => Bar
	            [biz] => Buz
	        )
	    [messages] => Array
	        (
	            [0] => stdClass Object
	                (
	                    [type] => info
	                    [text] => Endpoint: /api/v1/test/simple
	                )	
	        )
	)

### Get Request Response Status

This method allows to directly get the `[status]` part of the request response.  
The `status` is the HTTP status code associated with the response. 

> getRequestResponseStatus()

This method always return a string.

Usage example:

	// Get the request response status.
	$requestResponseStatus = $api->getRequestResponseStatus();

### Get Request Response Data

This method allows to directly get the `[data]` part of the request response.  

> getRequestResponseData()

This method always return a PHP object.

Usage example:

	// Get the request response data.
	$requestResponseData = $api->getRequestResponseData(); 

### Get Request Response Messages

This method allows to directly get the `[messages]` part of the request response.  

> getRequestResponseMessages()

This method always return a PHP array.

Usage example:

	// Get the request response messages.
	$requestResponseMessages = $api->getRequestResponseMessages(); 

### Get Request Info

Each request is made using the PHP cURL extension.  
This method allows to get the result of the function `curl_getinfo()`.  
See the official [PHP.net](https://www.php.net/manual/en/function.curl-getinfo.php) website for documentation. 

> getRequestInfo()

This method always return a PHP array.

Usage example:

	// Get the request info.
	$requestInfo = $api->getRequestInfo(); 

### Debug Start

This method allows to start the debugging mode.  
Every request executed after this method call will be taken in account for the debugging.

> debugStart()

This method return the class instance itself.  
For more convenient use, this method is "chainable".

Example:

	// Do a request and enable the debugging mode.
	$api->request('post', '/api/v1/test/simple')->debugStart();

### Debug Stop

This method allows to stop the debugging mode.  
Every request executed after this method call will be not taken in account for the debugging.

> debugStop()

This method return the class instance itself.  
For more convenient use, this method is "chainable".

Example:

	// Do a request and disable the debugging mode.
	$api->request('post', '/api/v1/test/simple')->debugStop();

### Debug Get

This method allows to get the result of the debugging data.  
This method call doesn't stop the debugging mode, so it can be called every time needed.
This is just a debugging output at the time "t".

> debugGet()

This method return a PHP array.  

Example:

	// Start the debugging mode.
	$api->debugStart();

	// Do a request #1.
	$api->request('post', '/api/v1/test/simple');

	// Do a request #2.
	$api->request('post', '/api/v1/test/simple');

	// Dump the debugging data (which contains requests #1 and #2).
	var_dump($api->debugGet());

	// Do a request #3.
	$api->request('post', '/api/v1/test/simple');

	// Do a request #4.
	$api->request('post', '/api/v1/test/simple');

	// Stop the debugging mode.
	$api->debugStop();

	// Dump the debugging data (which contains requests #1, #2, #3, #4).
	var_dump($api->debugGet());

### Debug Reset

This method allows to reset the result of the debugging data.   
This method call doesn't stop the debugging mode, but it erases every collected data until its call.

> debugReset()

This method return the class instance itself.  
For more convenient use, this method is "chainable".

Example #1:

	// Do a request and reset the debugging data.
	$api->request('post', '/api/v1/test/simple')->debugReset();

Example #2:

	// Start the debugging mode.
	$api->debugStart();

	// Do a request #1.
	$api->request('post', '/api/v1/test/simple');

	// Do a request #2.
	$api->request('post', '/api/v1/test/simple');

	// Dump the debugging data (which contains requests #1 and #2).
	var_dump($api->debugGet());

	// Reset the debugging data.
	$api->debugReset();

	// Do a request #3.
	$api->request('post', '/api/v1/test/simple');

	// Do a request #4.
	$api->request('post', '/api/v1/test/simple');

	// Stop the debugging mode.
	$api->debugStop();

	// Dump the debugging data (which contains requests #3 and #4).
	var_dump($api->debugGet());

## License

[MIT](https://choosealicense.com/licenses/mit/)
