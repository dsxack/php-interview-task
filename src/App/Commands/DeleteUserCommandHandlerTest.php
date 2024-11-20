<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\Timestamps;
use DmitriySmotrov\Interview\Domain\User\User;
use PHPUnit\Framework\TestCase;

class DeleteUserCommandHandlerTest extends TestCase {
    public function testHandle() {
        $user = User::fromStorage(
            new ID(1),
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
            Timestamps::fromStorage(DateTime::now(), null),
            new Notes('test notes'),
        );

        $repoMock = $this->createMock(Repository::class);
        $repoMock
            ->expects($this->once())
            ->method('update')
            ->willReturnCallback(function ($id, $callback) use ($user) {
                $callback(User::new(
                    new Name('johndoe1'),
                    new Email('johndoe1@domain.com'),
                    DateTime::now(),
                    new Notes('test notes'),
                ));
            });

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->once())
            ->method('log')
            ->with("User with ID 1 deleted");

        $handler = new DeleteUserCommandHandler($repoMock, $loggerMock);
        $handler->handle(new DeleteUserCommand($user->id()));
    }
}