<?php

namespace DmitriySmotrov\Interview\Domain\User;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class DomainTest extends TestCase {
    #[DataProvider('constructorProvider')]
    public function testConstructor($domain, $expected) {
        try {
            new Domain($domain);
            $this->assertTrue($expected);
        } catch (InvalidDomainException) {
            $this->assertFalse($expected);
        }
    }

    static public function constructorProvider(): array {
        return [
            'good' => ['domain.com', true],
            'do not have zone' => ['domain', false],
            'invalid symbols' => ['domain.com!', false],
        ];
    }
}
