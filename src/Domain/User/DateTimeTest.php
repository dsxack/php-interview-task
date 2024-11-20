<?php

namespace DmitriySmotrov\Interview\Domain\User;

use PHPUnit\Framework\TestCase;

class DateTimeTest extends TestCase {
    public function testNow() {
        // TODO: mock Carbon::now() to return a fixed date
        $dateTime = DateTime::now();
        $this->assertLessThanOrEqual(time(), strtotime($dateTime->toString()));
    }

    public function testFromString() {
        $dateTime = DateTime::fromString('2021-01-01 00:00:00');
        $this->assertEquals('2021-01-01 00:00:00', $dateTime->toString());
    }

    public function testBefore() {
        $dateTime1 = DateTime::fromString('2021-01-01 00:00:00');
        $dateTime2 = DateTime::fromString('2021-01-02 00:00:00');
        $this->assertTrue($dateTime1->before($dateTime2));
    }
}
