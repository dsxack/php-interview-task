<?php

namespace DmitriySmotrov\Interview\Domain\User;

/**
 * ID value object.
 *
 * Represents a user ID.
 *
 * @package DmitriySmotrov\Interview\Domain\User
 */
class ID {
    private int $value;

    /**
     * ID constructor.
     *
     * @param int $id The raw ID value
     * @throws InvalidIDException if the raw id not a positive integer
     */
    public function __construct(int $id) {
        if ($id <= 0) {
            throw new InvalidIDException('ID must be greater than 0');
        }
        $this->value = $id;
    }

    /**
     * Returns the ID as an integer.
     *
     * @return int
     */
    public function toInteger(): int {
        return $this->value;
    }
}
