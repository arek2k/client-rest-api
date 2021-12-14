<?php declare(strict_types=1);

namespace Arek2k\RestClient\Transport;

use CurlHandle;
use Exception;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ResponseInterface;

final class Curl implements TransportInterface
{

    private CurlHandle $curlHandle;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        if(!extension_loaded('curl')) {
            throw new Exception('The curl extension is not install');
        }

        $this->initCurl();
    }

    public function __destruct()
    {
        $this->closeCurl();
    }

    public function initCurl()
    {
        $this->curlHandle = curl_init();
    }

    public function closeCurl()
    {
        curl_close($this->curlHandle);
    }

    public function sendRequest(RequestInterface $request, array $options = []): ResponseInterface
    {
        // TODO: Implement sendRequest() method.
    }
}