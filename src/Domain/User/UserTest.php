<?php

namespace DmitriySmotrov\Interview\Domain\User;

use PHPUnit\Framework\TestCase;

class UserTest extends TestCase {
    public function testFromStorage() {
        $now = DateTime::now();
        $user = $this->testUser($now);

        $this->assertEquals(1, $user->id()->toInteger());
        $this->assertEquals('johndoe1', $user->name()->toString());
        $this->assertEquals('johndoe1@domain.com', $user->email()->toString());
        $this->assertEquals('Some notes', $user->notes()->toString());
        $this->assertEquals($now->toString(), $user->timestamps()->createdAt()->toString());
    }

    public function testDelete() {
        $now = DateTime::now();
        $user = $this->testUser($now);

        $this->assertNull($user->timestamps()->deletedAt());
        $user->delete($now);
        $this->assertEquals($now->toString(), $user->timestamps()->deletedAt()->toString());
    }

    public function testEdit() {
        $now = DateTime::now();
        $user = $this->testUser($now);

        $newName = new Name('johndoe2');
        $newEmail = new Email('johndoe2@domain.com');
        $newNotes = new Notes('Some new notes');

        $user->edit($newName, $newEmail, $newNotes);

        $this->assertEquals('johndoe2', $user->name()->toString());
        $this->assertEquals('johndoe2@domain.com', $user->email()->toString());
        $this->assertEquals('Some new notes', $user->notes()->toString());
    }

    private function testUser(DateTime $now): User {
        $id = new ID(1);
        $name = new Name('johndoe1');
        $email = new Email('johndoe1@domain.com');
        $ts = Timestamps::new($now);
        $notes = new Notes('Some notes');
        return User::fromStorage($id, $name, $email, $ts, $notes);
    }
}
