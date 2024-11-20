<?php

namespace DmitriySmotrov\Interview\Domain\User;

class Timestamps {
    private DateTime $createdAt;
    private ?DateTime $deletedAt;

    private function __construct(DateTime $createdAt, ?DateTime $deletedAt) {
        $this->createdAt = $createdAt;
        if ($deletedAt !== null && $deletedAt->before($createdAt)) {
            throw new InvalidTimestampsException('Deleted date cannot be before created date');
        }
        $this->deletedAt = $deletedAt;
    }

    public static function new(DateTime $now): Timestamps {
        return new Timestamps($now, null);
    }

    public static function fromStorage(DateTime $createdAt, ?DateTime $deletedAt): Timestamps {
        return new Timestamps($createdAt, $deletedAt);
    }

    public function createdAt(): DateTime {
        return $this->createdAt;
    }

    public function deletedAt(): ?DateTime {
        return $this->deletedAt;
    }

    public function delete(DateTime $now): Timestamps {
        return new Timestamps($this->createdAt, $now);
    }
}