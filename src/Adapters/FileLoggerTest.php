<?php

namespace DmitriySmotrov\Interview\Adapters;

use PHPUnit\Framework\TestCase;

class FileLoggerTest extends TestCase {
    public function testLog() {
        $logFile = fopen('php://memory', 'w+');
        $logger = new FileLogger($logFile);
        $logger->log('test', ['key' => 'value', 'key2' => 'value2']);

        rewind($logFile);
        $this->assertEquals("test key=value key2=value2\n", stream_get_contents($logFile));
    }
}
