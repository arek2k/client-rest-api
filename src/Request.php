<?php declare(strict_types=1);

namespace Arek2k\RestClient;

use Arek2k\RestClient\Transport\Curl;
use Arek2k\RestClient\Transport\TransportInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;
use Nyholm\Psr7\Factory\Psr17Factory;

class Request implements TransportInterface
{
    const GET = 'GET';
    const HEAD = 'HEAD';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';

    private TransportInterface $transport;
    private RequestFactoryInterface $requestFactory;

    public function __construct()
    {
        $this->transport = new Curl();
        $this->requestFactory = new Psr17Factory();
    }

    /**
     * @param string $url
     * @param array $headers
     * @return ResponseInterface
     */
    public function get(string $url, array $headers = []): ResponseInterface
    {
        return $this->request(self::GET, $url, $headers);
    }

    /**
     * @param string $url
     * @param array $headers
     * @return ResponseInterface
     */
    public function head(string $url, array $headers = []): ResponseInterface
    {
        return $this->request(self::HEAD, $url, $headers);
    }

    /**
     * @param string $url
     * @param array $headers
     * @param string $body
     * @return ResponseInterface
     */
    public function post(string $url, array $headers = [], string $body = ''): ResponseInterface
    {
        return $this->request(self::POST, $url, $headers, $body);
    }

    /**
     * @param string $url
     * @param array $headers
     * @param string $body
     * @return ResponseInterface
     */
    public function put(string $url, array $headers = [], string $body = ''): ResponseInterface
    {
        return $this->request( self::PUT, $url, $headers, $body);
    }

    /**
     * @param string $url
     * @param array $headers
     * @param string $body
     * @return ResponseInterface
     */
    public function delete(string $url, array $headers = [], string $body = ''): ResponseInterface
    {
        return $this->request(self::DELETE, $url, $headers, $body);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param string $body
     * @return ResponseInterface
     */
    public function request(string $method = self::GET, string $url,  array $headers = [], string $body = ''): ResponseInterface
    {
        $request = $this->createRequest($method, $url,  $headers, $body);
        return $this->sendRequest($request);
    }

    /**
     * @param string $method
     * @param string $url
     * @param array $headers
     * @param string $body
     * @return RequestInterface
     */
    protected function createRequest(string $method, string $url,  array $headers, string $body): RequestInterface
    {
        $request = $this->requestFactory->createRequest($method, $url);
        $request->getBody()->write($body);
        foreach ($headers as $name => $value) {
            $request = $request->withAddedHeader($name, $value);
        }
        return $request;
    }

    public function sendRequest(RequestInterface $request, array $options = []): ResponseInterface
    {
        return $this->transport->sendRequest($request, $options);
    }
}