<?php

namespace DmitriySmotrov\Interview\App\Services;

use DmitriySmotrov\Interview\Domain\User\Domain;

/**
 * Interface UserEmailUntrustedDomains
 *
 * Interface for checking if a domain is untrusted.
 *
 * @package DmitriySmotrov\Interview\App\Services
 */
interface UserEmailUntrustedDomains {
    public function isUntrusted(Domain $domain): bool;
}
