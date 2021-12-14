<?php declare(strict_types=1);

namespace Arek2k\RestClient\Transport;

class Curl
{

    private $curl;

    /**
     * @throws \Exception
     */
    public function __construct()
    {
        if(!extension_loaded('curl')) {
            throw new \Exception('The curl extension is not install');
        }

        $this->initCurl();

    }

    public function __destruct()
    {
        $this->closeCurl();
    }

    public function initCurl()
    {
        curl_init();
    }

    public function closeCurl()
    {
        curl_close();
    }

    public function exec()
    {

    }

    public function get() {}

    public function post() {}

    public function put() {}

    public function delete() {}
}