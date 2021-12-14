<?php declare(strict_types=1);

namespace Arek2k\RestClient;

use Arek2k\RestClient\Transport\Curl;
use Arek2k\RestClient\Transport\TransportInterface;
use Psr\Http\Message\ResponseInterface;

class Request
{
    // RFC7231
    const GET = 'GET';
    const HEAD = 'HEAD';
    const POST = 'POST';
    const PUT = 'PUT';
    const DELETE = 'DELETE';
    const CONNECT = 'CONNECT';
    const OPTIONS = 'OPTIONS';
    const TRACE = 'TRACE';

    private TransportInterface $transport;

    public function __construct()
    {
        $this->transport = new Curl();
    }

    public function get(string $url, array $headers = []): ResponseInterface
    {
        return $this->request($url, self::GET);
    }

    public function post(): ResponseInterface
    {
    }

    public function delete(): ResponseInterface
    {
    }

    public function put(): ResponseInterface
    {
    }

    /**
     * @param string $url
     * @param string $type
     * @param array $headers
     * @param array $options
     * @return ResponseInterface
     */
    public function request(string $url, string $type = self::GET, array $headers = [], array $options = []): ResponseInterface
    {

    }

}