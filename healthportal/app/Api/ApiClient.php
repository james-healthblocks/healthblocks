<?php

namespace App\Api;
use GuzzleHttp;
use GuzzleHttp\Psr7;
use GuzzleHttp\Exception\RequestException;

class ApiClient{
    protected $url = '';

    protected function get($endpoint, $headers=null){
        $client = new GuzzleHttp\Client();
        $retries = 0;
        while($retries < 3){
            try {
                $res = $client->request('GET', $this->url . $endpoint);
                break;
            }
            catch (RequestException $e){
                $retries++;
            } 
        }
        return [
            'request_url' => $this->url . $endpoint,
            'body' => json_decode((string)$res->getBody(), True),
            'status' => $res->getStatusCode(),
        ];
    }

    protected function post($endpoint, $content, $headers=null){
        $client = new GuzzleHttp\Client();
        $retries = 0;
        while($retries <3){
            try{
                $res = $client->request(
                    'POST',
                    $this->url . $endpoint,
                    [
                        'form_params' => $content,
                    ]
                );
                break;
            } catch (RequestException $e){
                $retries++;
            }
        }

        return [
            'request_url' => $this->url . $endpoint,
            'response' => json_decode((string)$res->getBody(), True),
            'status' => $res->getStatusCode(),
        ];
    }
}