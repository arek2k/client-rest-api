<?php declare(strict_types=1);

namespace Arek2k\RestClient;

use Nyholm\Psr7\Factory\Psr17Factory;
use Psr\Http\Message\ResponseInterface;

class Response
{
    private ResponseInterface $response;
    private Psr17Factory $responseFactory;

    public function __construct()
    {
        $this->responseFactory = new Psr17Factory();
        $this->response = $this->responseFactory->createResponse();
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
       return $this->response->getBody()->rewind();
    }
}