<?php

namespace DmitriySmotrov\Interview\Domain\User;

use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;

class TimestampsTest extends TestCase {
    #[DataProvider('fromStorageProvider')]
    public function testFromStorage($createdAtStr, $deletedAtStr, $expected) {
        $createdAt = DateTime::fromString($createdAtStr);
        $deletedAt = $deletedAtStr === null ? null : DateTime::fromString($deletedAtStr);

        try {
            $ts = Timestamps::fromStorage($createdAt, $deletedAt);
            $this->assertTrue($expected);
            $this->assertEquals($createdAt, $ts->createdAt());
            $this->assertEquals($deletedAt, $ts->deletedAt());
        } catch (InvalidTimestampsException) {
            $this->assertFalse($expected);
        }
    }

    public static function fromStorageProvider(): array {
        return [
            'good without deletedAt' => ['2021-01-01 00:00:00', null, true],
            'good with deletedAt' => ['2021-01-01 00:00:00', '2021-01-02 00:00:00', true],
            'bad with deletedAt before createdAt' => ['2021-01-02 00:00:00', '2021-01-01 00:00:00', false],
        ];
    }

    public function testNew() {
        $now = DateTime::fromString('2021-01-01 00:00:00');
        $timestamps = Timestamps::new($now);

        $this->assertEquals($now, $timestamps->createdAt());
        $this->assertNull($timestamps->deletedAt());
    }

    #[DataProvider('deleteProvider')]
    public function testDelete($createdAtStr, $deletedAtStr, $expected) {
        $createdAt = DateTime::fromString($createdAtStr);
        $deletedAt = $deletedAtStr === null ? null : DateTime::fromString($deletedAtStr);
        $ts = Timestamps::fromStorage($createdAt, null);

        try {
            $newTs = $ts->delete($deletedAt);
            $this->assertNull($ts->deletedAt());
            $this->assertEquals($deletedAt, $newTs->deletedAt());
            $this->assertTrue($expected);
        } catch (InvalidTimestampsException) {
            $this->assertFalse($expected);
        }
    }

    public static function deleteProvider(): array {
        return [
            'good with deletedAt before createdAt' => ['2021-01-01 00:00:00', '2021-01-02 00:00:00', true],
            'bad with deletedAt before createdAt' => ['2021-01-02 00:00:00', '2021-01-01 00:00:00', false],
        ];
    }
}
