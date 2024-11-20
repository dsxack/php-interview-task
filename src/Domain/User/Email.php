<?php

namespace DmitriySmotrov\Interview\Domain\User;

/**
 * Email value object.
 * Represents a user email address.
 *
 * @package DmitriySmotrov\Interview\Domain\User
 */
class Email {
    private string $value;
    private Domain $domain;

    /**
     * Email constructor.
     *
     * @param string $raw The raw email value
     * @throws InvalidEmailException if the email is invalid
     */
    public function __construct(string $raw) {
        $raw = trim($raw);
        if (!filter_var($raw, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidEmailException('Email is invalid');
        }
        if (strlen($raw) > 256) {
            throw new InvalidEmailException('Email is too long');
        }

        $domainStr = substr($raw, strpos($raw, '@') + 1);
        $this->domain = new Domain($domainStr);
        $this->value = $raw;
    }

    /**
     * Returns the email as a string.
     *
     * @return string
     */
    public function toString(): string {
        return $this->value;
    }

    /**
     * Checks if this email is equal to another email.
     *
     * @param Email $email
     * @return bool
     */
    public function equals(Email $email): bool {
        return $this->value === $email->value;
    }

    /**
     * Returns the domain part of the email.
     *
     * @return Domain
     */
    public function domain(): Domain {
        return $this->domain;
    }
}
