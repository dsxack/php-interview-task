<?php

namespace DmitriySmotrov\Interview\Domain\User;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NameTest extends TestCase {
    #[DataProvider('constructorProvider')]
    public function testConstructor($name, $expected) {
        try {
            new Name($name);
            $this->assertTrue($expected);
        } catch (InvalidNameException) {
            $this->assertFalse($expected);
        }
    }

    static public function constructorProvider(): array {
        return [
            'minimum length with numbers' => ['johndoe1', true],
            'with space at beginning' => [' johndoe1', true],
            'with space at ending' => ['johndoe1 ', true],
            'maximum length' => [str_repeat('a', 64), true],
            'with symbols' => ['john_doe', false],
            'with uppercase' => ['JohnDoe', false],
            'with spaces' => ['John Doe', false],
            'empty' => ['', false],
            'less than 8 symbols' => ['johndoe', false],
            'too long' => [str_repeat('a', 65), false]
        ];
    }
}