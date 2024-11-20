<?php

namespace DmitriySmotrov\Interview\Domain\User;

use Carbon\Carbon;

/**
 * DateTime value object.
 * It abstracts the Carbon library to make it easier to replace it with another library in the future.
 *
 * @package DmitriySmotrov\Interview\Domain\User
 */
class DateTime {
    private Carbon $value;

    /**
     * DateTime constructor.
     *
     * @param Carbon $value
     */
    private function __construct(Carbon $value) {
        $this->value = $value;
    }

    /**
     * Returns the current date and time.
     *
     * @return DateTime
     */
    public static function now(): DateTime {
        return new DateTime(Carbon::now());
    }

    /**
     * Creates a DateTime object from a string.
     *
     * @param string $value The date and time in a string format that Carbon can parse
     * @return DateTime
     */
    public static function fromString(string $value): DateTime {
        return new DateTime(Carbon::parse($value));
    }

    /**
     * Checks if this date and time is before another date and time.
     *
     * @param DateTime $another The date and time to compare with
     * @return bool
     */
    public function before(DateTime $another): bool {
        return $this->value->lt($another->value);
    }

    /**
     * Returns the date and time as a string.
     * Format: "Y-m-d H:i:s"
     *
     * @return string
     */
    public function toString(): string {
        return $this->value->toDateTimeString();
    }
}
