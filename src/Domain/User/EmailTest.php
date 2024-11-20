<?php

namespace DmitriySmotrov\Interview\Domain\User;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class EmailTest extends TestCase {
    #[DataProvider('constructorProvider')]
    public function testConstructor($email, $expected) {
        try {
            new Email($email);
            $this->assertTrue($expected);
        } catch (InvalidEmailException) {
            $this->assertFalse($expected);
        }
    }

    static public function constructorProvider(): array {
        return [
            'good email' => ['test@domain.com', true],
            'do not have zone' => ['test@domain', false],
            'do not have domain' => ['test', false],
            'do not have user' => ['@domain.com', false],
            'empty email' => ['', false],
            'too long email' => [str_repeat('a', 256) . '@domain.com', false],

            // TODO: FILTER_VALIDATE_EMAIL is not working with long emails
            //  it only support emails up to about 60 characters.
//            'maximum length' => [str_repeat('a', 60 - strlen('@domain.com')) . '@domain.com', true],
        ];
    }
}