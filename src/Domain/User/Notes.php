<?php

namespace DmitriySmotrov\Interview\Domain\User;

/**
 * Notes value object.
 *
 * Represents user notes.
 *
 * @package DmitriySmotrov\Interview\Domain\User
 */
class Notes {
    private string $value;

    /**
     * Notes constructor.
     *
     * @param string $raw The raw notes value
     * @throws InvalidNotesException if the notes are empty
     */
    public function __construct(string $raw) {
        $raw = trim($raw);
        if (strlen($raw) <= 0) {
            throw new InvalidNotesException('Notes is empty');
        }
        $this->value = $raw;
    }

    /**
     * Returns the notes as a string.
     *
     * @return string
     */
    public function toString(): string {
        return $this->value;
    }
}
