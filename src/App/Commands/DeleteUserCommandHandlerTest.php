<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\Adapters\FileLogger;
use DmitriySmotrov\Interview\Adapters\InMemoryUserRepository;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\User;
use PHPUnit\Framework\TestCase;

class DeleteUserCommandHandlerTest extends TestCase {
    public function testHandle() {
        $users = new InMemoryUserRepository();
        $user = User::new(
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
            DateTime::now(),
            new Notes('test notes'),
        );
        $users->create($user);

        $handler = $this->newHandler($users);

        $this->assertNull($user->timestamps()->deletedAt());
        $handler->handle(new DeleteUserCommand($user->id()));

        $user = $users->find($user->id());
        $this->assertNotNull($user->timestamps()->deletedAt());
    }

    private function newHandler(Repository $users): DeleteUserCommandHandler {
        $logFile = fopen('php://memory', 'w+');
        $logger = new FileLogger($logFile);
        return new DeleteUserCommandHandler($users, $logger);
    }
}