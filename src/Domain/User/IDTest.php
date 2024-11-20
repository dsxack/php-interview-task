<?php

namespace DmitriySmotrov\Interview\Domain\User;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class IDTest extends TestCase {
    #[DataProvider('constructorProvider')]
    public function testConstructor($value, $expected) {
        try {
            new ID($value);
            $this->assertTrue($expected);
        } catch (InvalidIDException) {
            $this->assertFalse($expected);
        }
    }

    static public function constructorProvider(): array {
        return [
            'good id' => [1, true],
            'good id 2' => [2, true],
            'less zero' => [-5, false],
            'zero' => [0, false],
        ];
    }
}