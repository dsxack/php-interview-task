<?php

namespace DmitriySmotrov\Interview\Adapters;

use DmitriySmotrov\Interview\App\Services\UserEmailUntrustedDomains;
use DmitriySmotrov\Interview\Domain\User\Domain;

/**
 * InMemoryUserEmailUntrustedDomains it is a UserEmailUntrustedDomains implementation that stores untrusted domains in memory.
 *
 * @package DmitriySmotrov\Interview\Adapters
 */
class InMemoryUserEmailUntrustedDomains implements UserEmailUntrustedDomains {
    /** @var Domain[] */
    private array $domains = [];

    /**
     * InMemoryUserEmailUntrustedDomains constructor.
     * @param Domain[] $domains
     */
    public function __construct(array $domains)
    {
        $this->domains = $domains;
    }

    /**
     * Checks if the domain is untrusted.
     *
     * @param Domain $domain
     * @return bool
     */
    public function isUntrusted(Domain $domain): bool
    {
        foreach ($this->domains as $untrustedDomain) {
            if ($untrustedDomain->equals($domain)) {
                return true;
            }
        }
        return false;
    }

}