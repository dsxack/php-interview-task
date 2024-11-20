<?php

namespace DmitriySmotrov\Interview\Domain\User;

/**
 * Name value object.
 *
 * Represents a user name.
 *
 * @package DmitriySmotrov\Interview\Domain\User
 */
class Name {
    private string $value;

    /**
     * Name constructor.
     *
     * @param string $raw The raw name value
     * @throws InvalidNameException if the name is too short or contains invalid characters
     */
    public function __construct(string $raw) {
        $raw = trim($raw);
        if (strlen($raw) < 8) {
            throw new InvalidNameException('Name is too short');
        }
        if (strlen($raw) > 64) {
            throw new InvalidNameException('Name is too long');
        }
        if (!preg_match('/^[a-z0-9]+$/i', $raw)) {
            throw new InvalidNameException('Name contains invalid characters');
        }
        $this->value = $raw;
    }

    /**
     * Returns the name as a string.
     *
     * @return string
     */
    public function toString(): string {
        return $this->value;
    }

    /**
     * Checks if this name is equal to another name.
     *
     * @param Name $another The name to compare with
     * @return bool
     */
    public function equals(Name $another): bool {
        return $this->value === $another->value;
    }

    /**
     * Checks if this name contains a substring.
     *
     * @param string $substr The substring to search for
     * @return bool
     */
    public function contains(string $substr): bool {
        return stripos($this->value, $substr) !== false;
    }
}
