<?php declare(strict_types=1);

namespace Arek2k\RestClient\Auth;

use Arek2k\RestClient\Transport\TransportInterface;

class Basic implements Auth
{
    private string $username;
    private string $password;

    public function __construct(string $username, string $password)
    {
        $this->username = $username;
        $this->password = $password;
    }

    public function beforeSend(TransportInterface $transport): void
    {
        $transport->setOption(CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        $transport->setOption(CURLOPT_USERPWD, base64_encode($this->username . ':' . $this->password));
    }

}