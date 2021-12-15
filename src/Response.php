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
     * @param string $data
     */
    public function setStatus(string $data): void
    {
        $parts = explode(' ', $data, 3);
        $this->response = $this->response->withStatus((int) $parts[1], $parts[2] ?? '');
        $this->response = $this->response->withProtocolVersion((string) substr($parts[0], 5));
    }

    /**
     * @param string $data
     */
    public function addHeader(string $data): void
    {
        list($key, $value) = explode(':', $data, 2);
        $this->response = $this->response->withAddedHeader(trim($key), trim($value));
    }

    /**
     * @param string $data
     * @return int
     */
    public function writeBody(string $data): int
    {
        return $this->response->getBody()->write($data);
    }

    /**
     * @return ResponseInterface
     */
    public function getResponse(): ResponseInterface
    {
       return $this->response->getBody()->rewind();
    }
}