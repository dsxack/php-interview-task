<?php

namespace DmitriySmotrov\Interview\Adapters;

use DmitriySmotrov\Interview\App\Services\Logger;

/**
 * FileLogger it is a Logger implementation that writes logs to a file.
 *
 * @package DmitriySmotrov\Interview\Adapters
 */
class FileLogger implements Logger {
    private $file;

    /**
     * FileLogger constructor.
     * @param resource $file
     */
    public function __construct($file) {
        $this->file = $file;
    }

    /**
     * Writes a log message to the file.
     *
     * @param string $message
     * @param array $fields
     */
    public function log(string $message, $fields = []): void {
        if (!empty($fields)) {
            foreach ($fields as $key => $value) {
                $message .= " $key=$value";
            }
        }
        fwrite($this->file, $message . PHP_EOL);
    }
}
