<?php

namespace DmitriySmotrov\Interview\Domain\User;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class NotesTest extends TestCase {
    #[DataProvider('constructorProvider')]
    public function testConstructor($notes, $expected) {
        try {
            new Notes($notes);
            $this->assertTrue($expected);
        } catch (InvalidNotesException) {
            $this->assertFalse($expected);
        }
    }

    static public function constructorProvider(): array {
        return [
            'good' => ['johndoe123', true],
            'empty' => ['', false],
        ];
    }
}