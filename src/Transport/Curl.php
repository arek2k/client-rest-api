<?php declare(strict_types=1);

namespace Arek2k\RestClient\Transport;

use Arek2k\RestClient\Response;
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
        if (!extension_loaded('curl')) {
            throw new Exception('The curl extension is not install');
        }

        $this->initCurl();
    }

    public function __destruct()
    {
        $this->closeCurl();
    }

    public function initCurl(): void
    {
        $this->curlHandle = curl_init();
        $this->setOption(CURLINFO_HEADER_OUT, true);
        $this->setOption(CURLOPT_HEADER, false);
        $this->setOption(CURLOPT_RETURNTRANSFER, true);
        $this->setOption(CURLOPT_FAILONERROR, false);
    }

    public function closeCurl(): void
    {
        curl_close($this->curlHandle);
    }

    /**
     * @param int $option
     * @param mixed $value
     * @return void
     */
    public function setOption(int $option, mixed $value): void
    {
        curl_setopt($this->curlHandle, $option, $value);
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return ResponseInterface
     */
    public function sendRequest(RequestInterface $request, array $options = []): ResponseInterface
    {
        $response = $this->prepareCurl($request, $options);

        curl_exec($this->curlHandle);
        $this->parseError(curl_errno($this->curlHandle));

        return $response->getResponse();
    }

    /**
     * @param RequestInterface $request
     * @param array $options
     * @return Response
     */
    private function prepareCurl(RequestInterface $request, array $options = []): Response
    {
        $this->setOption(CURLOPT_CUSTOMREQUEST, $request->getMethod());
        $this->setOption(CURLOPT_URL, $request->getUri()->__toString());
        $this->setOption(CURLOPT_HTTPHEADER, $this->setHeaders($request->getHeaders()));
        $this->setOptionsForMethod($request);
        $this->setOptionsFromParameter($options);
        $response = new Response();

        $this->setOption(CURLOPT_HEADERFUNCTION, function ($ch, $data) use ($response) {
            $str = trim($data);
            if (strlen($str) > 0) {
                if (str_starts_with(strtolower($str), 'http/')) {
                    $response->setStatus($str);
                } else {
                    $response->addHeader($str);
                }
            }

            return strlen($data);
        });

        $this->setOption(CURLOPT_WRITEFUNCTION, function ($ch, $data) use ($response) {
            return $response->writeBody($data);
        });

        return $response;
    }

    /**
     * @param array $options
     */
    private function setOptionsFromParameter(array $options): void
    {
        //TODO: Implement this method
    }

    /**
     * @param RequestInterface $request
     */
    private function setOptionsForMethod(RequestInterface $request): void
    {
        switch (strtoupper($request->getMethod())) {
            case 'HEAD':
                $this->setOption(CURLOPT_NOBODY, true);
                break;

            case 'GET':
                $this->setOption(CURLOPT_HTTPGET, true);
                break;

            case 'POST':
            case 'PUT':
            case 'DELETE':
            case 'PATCH':
            case 'OPTIONS':
                $this->setOption(CURLOPT_POST, true);
                $body = $request->getBody();
                $bodySize = $body->getSize();
                if ($bodySize !== 0) {
                    $this->setOption(CURLOPT_POSTFIELDS, (string)$body);
                }
        }

    }

    /**
     * @param array $headers
     * @return array
     */
    private function setHeaders(array $headers): array
    {
        $httpHeaders = [];

        foreach ($headers as $key => $values) {
            if (!is_array($values)) {
                $httpHeaders[] = sprintf('%s: %s', $key, $values);
            } else {
                foreach ($values as $value) {
                    $httpHeaders[] = sprintf('%s: %s', $key, $value);
                }
            }
        }

        return $httpHeaders;
    }


    /**
     * @param int $errno
     */
    private function parseError(int $errno): void
    {
        switch ($errno) {
            case CURLE_OK:
                break;
            case CURLE_COULDNT_RESOLVE_PROXY:
            case CURLE_COULDNT_RESOLVE_HOST:
            case CURLE_COULDNT_CONNECT:
            case CURLE_OPERATION_TIMEOUTED:
            case CURLE_SSL_CONNECT_ERROR:
            case CURLE_ABORTED_BY_CALLBACK:
            default:
                throw new \RuntimeException(curl_error($this->curlHandle), $errno);
        }
    }
}