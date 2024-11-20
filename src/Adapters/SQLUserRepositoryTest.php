<?php

namespace DmitriySmotrov\Interview\Adapters;

use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\User;
use Exception;
use PDO;
use PHPUnit\Framework\TestCase;

class SQLUserRepositoryTest extends TestCase {
    public function testCreate(): void {
        $repository = $this->newRepository();
        $user = $this->newTestUser('johndoe1', 'johndoe1@domain.com');
        $repository->create($user);

        $foundUser = $repository->find($user->id());
        $this->assertEquals($user->id(), $foundUser->id());
        $this->assertEquals($user->name(), $foundUser->name());
        $this->assertEquals($user->email(), $foundUser->email());
        $this->assertEquals($user->timestamps(), $foundUser->timestamps());
        $this->assertEquals($user->notes(), $foundUser->notes());
    }

    public function testIncrementalIds(): void {
        $repository = $this->newRepository();
        $user1 = $this->newTestUser('johndoe1', 'johndoe1@domain.com');
        $user2 = $this->newTestUser('johndoe2', 'johndoe2@domain.com');
        $repository->create($user1);
        $repository->create($user2);

        $this->assertEquals(1, $user1->id()->toInteger());
        $this->assertEquals(2, $user2->id()->toInteger());
    }

    public function testCreateWithExistingEmail(): void {
        $repository = $this->newRepository();
        $user1 = $this->newTestUser('johndoe1', 'johndoe1@domain.com');
        $user2 = $this->newTestUser('johndoe2', 'johndoe1@domain.com');
        $repository->create($user1);

        $this->expectException(Exception::class);
        $repository->create($user2);
    }

    public function testCreateWithExistingUsername(): void {
        $repository = $this->newRepository();
        $user1 = $this->newTestUser('johndoe1', 'johndoe1@domain.com');
        $user2 = $this->newTestUser('johndoe1', 'johndoe2@domain.com');
        $repository->create($user1);

        $this->expectException(Exception::class);
        $repository->create($user2);
    }

    public function testUpdateWithExistingEmail(): void {
        $repository = $this->newRepository();
        $user1 = $this->newTestUser('johndoe1', 'johndoe1@domain.com');
        $user2 = $this->newTestUser('johndoe2', 'johndoe2@domain.com');
        $repository->create($user1);
        $repository->create($user2);

        $this->expectException(Exception::class);
        $repository->update($user2->id(), function (User $user) use ($user1, $user2) {
            $user->edit(
                $user2->name(),
                $user1->email(), // same email as user1
                $user2->notes(),
            );
        });
    }

    public function testUpdateWithExistingUsername(): void {
        $repository = $this->newRepository();
        $user1 = $this->newTestUser('johndoe1', 'johndoe1@domain.com');
        $user2 = $this->newTestUser('johndoe2', 'johndoe2@domain.com');
        $repository->create($user1);
        $repository->create($user2);

        $this->expectException(Exception::class);
        $repository->update($user2->id(), function (User $user) use ($user1, $user2) {
            $user->edit(
                $user1->name(), // same name as user1
                $user2->email(),
                $user2->notes(),
            );
        });
    }

    public function testUpdate(): void {
        $repository = $this->newRepository();
        $user = $this->newTestUser('johndoe1', 'johndoe1@domain.com');
        $repository->create($user);

        $repository->update($user->id(), function (User $user) {
            $user->edit(
                new Name('johndoe2'),
                new Email('johndoe2@domain.com'),
                new Notes('test new notes'),
            );
        });

        $updatedUser = $repository->find($user->id());
        $this->assertEquals('johndoe2', $updatedUser->name()->toString());
        $this->assertEquals('johndoe2@domain.com', $updatedUser->email()->toString());
        $this->assertEquals('test new notes', $updatedUser->notes()->toString());
    }

    private function newRepository(): SQLUserRepository {
        // TODO: test with mysql docker testcontainer.
        $pdo = new PDO('sqlite::memory:');
        $pdo->exec('create table users
(
    id integer primary key autoincrement,
    name varchar(64) not null,
    email varchar(256) not null,
    created datetime not null,
    deleted datetime null,
    notes text null
);

create unique index users_email_uindex
    on users (email);

create unique index users_name_uindex
    on users (name);');
        return new SQLUserRepository($pdo, true);
    }

    private function newTestUser($username, $email): User {
        return User::new(
            new Name($username),
            new Email($email),
            DateTime::fromString('2021-01-01 00:00:00'),
            new Notes('test notes'),
        );
    }
}