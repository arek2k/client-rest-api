<?php declare(strict_types=1);

namespace Arek2k\RestClient\Transport;

use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

interface TransportInterface
{
    /**
     * @param RequestInterface $request
     * @param array $options
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request, array $options = []): ResponseInterface;
}