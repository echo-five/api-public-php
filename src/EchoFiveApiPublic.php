<?php

namespace EchoFiveApiPublic;

use DateTime;
use Exception;

/**
 * Echo-Five API Public PHP.
 *
 * @author Matthieu Roy <m@matthieuroy.be>
 * @license https://github.com/echo-five/api-public-php/blob/master/LICENSE MIT
 * @copyright 2023 Matthieu Roy
 * @link https://github.com/echo-five/api-public-php Documentation.
 */
class EchoFiveApiPublic
{
    /**
     * The API host.
     *
     * @var string
     */
    private string $_apiHost;
    
    /**
     * The API key.
     *
     * @var string
     */
    private string $_apiKey;
    
    /**
     * The API Request info.
     *
     * @var array
     */
    private array $_apiRequestInfo;
    
    /**
     * The API Request response.
     *
     * @var string
     */
    private string $_apiRequestResponse;
    
    /**
     * The API secret.
     *
     * @var string
     */
    private string $_apiSecret;
    
    /**
     * The debug status.
     *
     * @var bool
     */
    private bool $_debug;
    
    /**
     * The debug data.
     *
     * @var array
     */
    private array $_debugData;
    
    /**
     * Constructor.
     *
     * @param string $apiHost
     *  The API host URL.
     * @param string $apiKey
     *  The API key.
     * @param string $apiSecret
     *  The API secret (optional).
     *
     * @throws \Exception
     */
    public function __construct(string $apiHost, string $apiKey, string $apiSecret = '')
    {
        // Checks if the URL of the API host is provided.
        if (empty($apiHost))
        {
            // Throw an exception.
            throw new Exception('Please provide the API URL!');
        }
        // Set the API host URL.
        $this->_apiHost = $apiHost;
        // Checks if the API key is provided.
        if (empty($apiKey))
        {
            // Throw an exception.
            throw new Exception('Please provide the API Key!');
        }
        // Set the API key.
        $this->_apiKey = $apiKey;
        // Set the API secret.
        $this->_apiSecret = $apiSecret ?? '';
    }
    
    /**
     * Debug - Get the debug data.
     *
     * @return array
     */
    public function debugGet(): array
    {
        // Get the debug data.
        $debugData = $this->_debugData ?? [];
        // Alter the values.
        if (isset($debugData['requests']['time']))
        {
            // Add time unit for better understanding.
            $debugData['requests']['time'] .= ' SECS';
        }
        // Return.
        return $debugData;
    }
    
    /**
     * Debug - Reset the debug data.
     *
     * @return EchoFiveApiPublic
     * @throws \Exception
     */
    public function debugReset(): EchoFiveApiPublic
    {
        // Reset the data.
        $this->_debugData = [];
        // Start the debug.
        $this->debugStart();
        // Return.
        return $this;
    }
    
    /**
     * Debug - Enable the debugging mode.
     *
     * @return EchoFiveApiPublic
     * @throws \Exception
     */
    public function debugStart(): EchoFiveApiPublic
    {
        // Enable.
        $this->_debug = true;
        // Reset the debug data if needed.
        if (empty($this->_debugData))
        {
            // Initialize the debug data.
            $this->_debugInitialize();
        }
        // Debug.
        if ($this->_debug)
        {
            // Message.
            $this->_debugAddMessage('Start debug.');
        }
        // Return.
        return $this;
    }
    
    /**
     * Debug - Disable the debugging mode.
     *
     * @return EchoFiveApiPublic
     * @throws \Exception
     */
    public function debugStop(): EchoFiveApiPublic
    {
        // Debug.
        if ($this->_debug)
        {
            // Message.
            $this->_debugAddMessage('Stop debug.');
        }
        // Disable.
        $this->_debug = false;
        // Return.
        return $this;
    }
    
    /**
     * Request - Get the request info.
     *
     * @return array
     */
    public function getRequestInfo(): array
    {
        // Return.
        return $this->_apiRequestInfo ?? [];
    }
    
    /**
     * Request - Get the request response.
     *
     * @param bool $decode
     *  If the response must be decoded (default).
     *
     * @return string|object
     */
    public function getRequestResponse(bool $decode = true)
    {
        // Return.
        return ($decode) ? json_decode($this->_apiRequestResponse) : $this->_apiRequestResponse;
    }
    
    /**
     * Request - Get the request response data.
     *
     * @return object
     */
    public function getRequestResponseData(): object
    {
        // Return.
        return $this->getRequestResponse()->data ?? (object) [];
    }
    
    /**
     * Request - Get the request response messages.
     *
     * @return array
     */
    public function getRequestResponseMessages(): array
    {
        // Return.
        return $this->getRequestResponse()->messages ?? [];
    }
    
    /**
     * Request - Get the request response status.
     *
     * @return string
     */
    public function getRequestResponseStatus(): string
    {
        // Return.
        return (string) $this->getRequestResponse()->status ?? '';
    }
    
    /**
     * Request - Execute a request.
     *
     * @param string $requestType
     *  The request type.
     * @param string $requestEndpoint
     *  The request endpoint.
     * @param array $requestParams
     *  The request parameters (optional).
     * @param string $requestMode
     *  The request mode.
     *  This is the mode used to send the request params.
     *  Allowed values: json (default), form, http.
     *
     * @return EchoFiveApiPublic
     * @throws \Exception
     */
    public function request(string $requestType, string $requestEndpoint, array $requestParams = [], string $requestMode = 'json'): EchoFiveApiPublic
    {
        // Initialize the request.
        $request = curl_init();
        // Initialize the request URL.
        $requestUrl = $this->_requestGetUrl(trim($requestEndpoint));
        // Set the request headers.
        $requestHeaders = [
            'Authorization: Bearer ' . $this->_apiKey,
        ];
        // Set the request signature.
        if (! empty($this->_apiSecret))
        {
            // Set the signature header.
            $requestHeaders[] = "X-API-Signature: " . $this->_requestGetSignature($requestParams);
        }
        // Switch on request type.
        switch (strtoupper(trim($requestType)))
        {
            // GET.
            case 'GET':
                // Set the request URL.
                $requestUrl = (! empty($requestParams)) ? sprintf('%s?%s', $requestUrl, http_build_query($requestParams)) : $requestUrl;
                break;
            // POST.
            case 'POST':
            default:
                // Set the POST type.
                curl_setopt($request, CURLOPT_POST, 1);
                // Set the POST fields.
                if (! empty($requestParams))
                {
                    // Switch on request mode.
                    switch (strtoupper(trim($requestMode)))
                    {
                        // FORM.
                        case 'FORM':
                            // Add the correct header.
                            $requestHeaders[] = 'Content-Type: multipart/form-data';
                            // Set the POST fields.
                            curl_setopt($request, CURLOPT_POSTFIELDS, $requestParams);
                            break;
                        // HTTP.
                        case 'HTTP':
                            // Set the POST fields.
                            curl_setopt($request, CURLOPT_POSTFIELDS, sprintf('%s?%s', $requestUrl, http_build_query($requestParams)));
                            break;
                        // JSON.
                        case 'JSON':
                        default:
                            // Add the correct header.
                            $requestHeaders[] = 'Content-Type: application/json';
                            // Set the POST fields.
                            curl_setopt($request, CURLOPT_POSTFIELDS, ((! empty($requestParams)) ? json_encode($requestParams) : '{}'));
                            break;
                    }
                }
                break;
        }
        // Set the request URL.
        curl_setopt($request, CURLOPT_URL, $requestUrl);
        // Set the headers.
        curl_setopt($request, CURLOPT_HTTPHEADER, $requestHeaders);
        // Set remaining options.
        curl_setopt($request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_2TLS);
        curl_setopt($request, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($request, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($request, CURLOPT_TCP_FASTOPEN, true);
        if (! empty($this->_debug))
        {
            curl_setopt($request, CURLOPT_SSL_VERIFYPEER, false);
        }
        // Execute the request.
        $requestResponse = curl_exec($request);
        // Set the request response attribute.
        $this->_apiRequestResponse = $requestResponse;
        // Set the request info attribute.
        $this->_apiRequestInfo = curl_getinfo($request);
        // Check the request error.
        if (curl_errno($request))
        {
            // Throw exception.
            throw new Exception(curl_error($request));
        }
        // Close the request.
        curl_close($request);
        // Debug.
        if (! empty($this->_debug))
        {
            // Time + Increment + Message.
            $this->_debugAddRequestTime(($this->_apiRequestInfo['total_time'] ?? 0))->_debugAddRequestCount()->_debugAddMessage('Request | ' . $requestUrl);
        }
        // Return.
        return $this;
    }
    
    /**
     * Debug - Add debug message.
     *
     * @param string $message
     *  The trace $message.
     *
     * @return EchoFiveApiPublic
     * @throws \Exception
     */
    private function _debugAddMessage(string $message): EchoFiveApiPublic
    {
        // Set the timestamp.
        $timestamp = (new DateTime())->format('Y-m-d\TH:i:s.uP');
        // Set the message.
        $this->_debugData['requests']['trace'][$timestamp] = $message;
        // Return.
        return $this;
    }
    
    /**
     * Debug - Add debug request count.
     *
     * @return EchoFiveApiPublic
     */
    private function _debugAddRequestCount(): EchoFiveApiPublic
    {
        // Increment.
        $this->_debugData['requests']['count']++;
        // Return.
        return $this;
    }
    
    /**
     * Debug - Add debug request time.
     *
     * @param float $time
     *  The time.
     *
     * @return EchoFiveApiPublic
     */
    private function _debugAddRequestTime(float $time): EchoFiveApiPublic
    {
        // Increment.
        $this->_debugData['requests']['time'] = $this->_debugData['requests']['time'] + $time;
        // Return.
        return $this;
    }
    
    /**
     * Debug - Initialize the debug data.
     *
     * @return EchoFiveApiPublic
     * @throws \Exception
     */
    private function _debugInitialize(): EchoFiveApiPublic
    {
        // Initialize.
        $this->_debugData = [
            'requests' => [
                'time' => 0,
                'count' => 0,
                'trace' => [],
            ],
        ];
        // Return.
        return $this;
    }
    
    /**
     * Request - Get the request signature.
     *
     * @param array $requestParams
     *  The request parameters.
     *
     * @return string
     */
    private function _requestGetSignature(array &$requestParams): string
    {
        // Sort the request params.
        ksort($requestParams);
        // Return.
        return hash_hmac('sha256', json_encode($requestParams), $this->_apiSecret);
    }
    
    /**
     * Request - Get the request URL.
     *
     * @param string $requestEndpoint
     *  The request endpoint.
     *
     * @return string
     */
    private function _requestGetUrl(string $requestEndpoint): string
    {
        // Initialize.
        $requestUrl = $requestEndpoint;
        // If the host is provided in class instantiation + request URL don't start by http(s)://
        if (! empty($this->_apiHost) && ! preg_match('#^https?://#i', $requestUrl))
        {
            // Add the host if the host is missing but provided in instantiation.
            $requestUrl = $this->_apiHost . '/' . ltrim($requestUrl, '/');
        }
        // Return.
        return $requestUrl;
    }
}
