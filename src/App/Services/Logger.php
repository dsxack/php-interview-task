<?php

namespace DmitriySmotrov\Interview\App\Services;

/**
 * Interface Logger
 *
 * This interface is used to define the contract for the logger service.
 *
 * @package DmitriySmotrov\Interview\App\Services
 */
interface Logger {
    public function log(string $message, $fields = []): void;
}
