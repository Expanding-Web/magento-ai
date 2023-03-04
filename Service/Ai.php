<?php
namespace ExpandingWeb\Ai\Service;

use GuzzleHttp\Client;

class Ai
{
    public const ENDPOINT_URL = 'https://www.expandingweb.com/api/v1';

    /**
     * @var Client
     */
    private $client;

    /**
     * @param Client $client
     */
    public function __construct(
        Client $client
    ) {
        $this->client = $client;
    }

    /**
     * Return base url with path
     *
     * @param string $token
     * @param string $params
     *
     * @return string|null
     */
    public function getResponse(string $token, string $params)
    {
        $headers = [
            "Content-Type" => "application/json",
            "Authorization" => "Bearer ".$token
        ];
        $options = [
            "headers" => $headers,
            "timeout" => 60,
            "body" => $params
        ];
        $response = $this->client->post(self::ENDPOINT_URL, $options);

        $result = $response->getBody()->getContents();

        if ($result) {
            return $result;
        }

        return null;
    }
}
