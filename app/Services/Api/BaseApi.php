<?php

namespace App\Services\Api;

use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class BaseApi
{

    public $baseUrl;
    public $auth = [];

    public function __construct(string $baseUrl = '')
    {
        $this->setBaseUrl($baseUrl);
        $this->auth = [];
    }

    protected function setBaseUrl(string $baseUrl)
    {
        $this->baseUrl = $baseUrl;
    }

    protected function setAuth($username, $password)
    {

        $this->auth = [
            'auth' => [
                $username,
                $password
            ]
        ];
    }

    private function setClient()
    {
        $options = array_merge([
            'base_uri' => $this->baseUrl,
        ], $this->auth);

        return new Client($options);
    }

    protected function call(string $method, string $path, $params = null, array $options = [])
    {

        //Set base options
        if (!array_key_exists('headers', $options)) {
            $options['headers'] = [];
        }
        $options['headers']['Accept'] = 'application/json';
        $options['headers']['Authorization'] = "Basic amFtZXMudG9kZEBpbnRlcmZvbGlvLmNvbTpHcjAwdnkyNw==";
        //Build request params
        $this->buildParams($method, $params, $options);

        // log home api call request
        Log::info("API - Request:\n" . json_encode([
                $this->baseUrl,
                $method, $path, $options
            ]));
        //Perform the request
        $client = $this->setClient();
        $response = $client->request($method, $path, $options);
        Log::info("API - Response:\n" . json_encode(
                (method_exists($response, 'getBody')) ? (string)$response->getBody() : $response)
        );

        return $response;
    }//!End function, call

    /**
     * @param string $method
     * @param $params
     * @param array $options
     */
    protected function buildParams(string $method, $params, array &$options)
    {
        //If $params is a string, assume the call needs to post directly to the body
        if ($params !== null) {
            if (is_string($params)) {
                $options['body'] = $params;
            } else {
                switch (strtoupper($method)) {
                    case 'GET':
                        if (is_array($params)) {
                            $options['query'] = $params;
                        }
                        break;
                    case 'POST':
                    case 'PUT':
                    case 'PATCH':
                    case 'DELETE':
                    default:
                        if (is_object($params) && is_a($params, \stdClass::class)) {
                            //stdClass, assume it's a JSON object
                            $options['json'] = $params;
                        } else {
                            if (is_array($params)) {
                                $options['body'] = preg_replace(
                                    '/%5B[0-9]+%5D/simU',
                                    '%5B%5D',
                                    http_build_query($params));
                                $options['headers']['Content-Type'] = 'application/x-www-form-urlencoded';
                                $options['headers']['Accept'] = 'application/json';
                            }
                        }
                        break;
                    case 'HEAD':
                    case 'OPTIONS':
                    case 'CONNECT':
                        //Do nothing
                        break;
                }//!End switch
            }//!End if/else, string
        }//!End if, null
    }

}
