<?php declare(strict_types=1);

namespace Arek2k\RestClient\Auth;

use Arek2k\RestClient\Transport\TransportInterface;

interface Auth
{
    public function beforeSend(TransportInterface $transport): void;
}
