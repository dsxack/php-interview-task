<?php

namespace DmitriySmotrov\Interview\Domain\User;

/**
 * Domain value object.
 * It represents a domain name with a zone.
 * It validates the domain name and provides methods to compare domain names.
 *
 * @package DmitriySmotrov\Interview\Domain\User
 */
class Domain {
    private string $value;

    public function __construct(string $raw) {
        $raw = trim($raw);
        if (!filter_var($raw, FILTER_VALIDATE_DOMAIN)) {
            throw new InvalidDomainException('Domain is invalid');
        }
        if (!str_contains($raw, '.')) {
            throw new InvalidDomainException('Domain do not have zone');
        }
        if (!preg_match('/^[a-z0-9.-]+$/i', $raw)) {
            throw new InvalidDomainException('Domain contains invalid characters');
        }

        $this->value = $raw;
    }

    public function equals(Domain $domain): bool {
        return $this->value === $domain->value;
    }

    public function toString(): string {
        return $this->value;
    }
}
