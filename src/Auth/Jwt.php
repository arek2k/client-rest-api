<?php declare(strict_types=1);

namespace Arek2k\RestClient\Auth;

use Arek2k\RestClient\Transport\TransportInterface;
use InvalidArgumentException;

class Jwt implements Auth
{
    private string $token;

    public function __construct(string $token)
    {
        if (empty($token)) {
            throw new InvalidArgumentException('You must provide token');
        }

        $this->token = $token;
    }

    public function beforeSend(TransportInterface $transport): void
    {
        $transport->setOption(CURLOPT_HTTPAUTH, CURLAUTH_BEARER);
        $transport->setOption(CURLOPT_XOAUTH2_BEARER, sprintf('Bearer %s', $this->token));
    }
}