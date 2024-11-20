<?php

namespace DmitriySmotrov\Interview\App\Queries;

use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\Timestamps;
use DmitriySmotrov\Interview\Domain\User\User;
use DmitriySmotrov\Interview\Domain\User\UserNotFoundException;
use PHPUnit\Framework\TestCase;

class UserQueryHandlerTest extends TestCase {
    public function testHandle() {
        $user = User::fromStorage(
            new ID(1),
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
            Timestamps::fromStorage(DateTime::now(), null),
            new Notes('test notes'),
        );

        $repoMock = $this->createMock(UserQueryReadModel::class);
        $repoMock
            ->expects($this->once())
            ->method('find')
            ->with($user->id())
            ->willReturn($user);

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->never())
            ->method('log');

        $handler = new UserQueryHandler($repoMock, $loggerMock);
        $userQueryResponse = $handler->handle(new UserQuery($user->id()));

        $this->assertEquals($user->id(), $userQueryResponse->id());
        $this->assertEquals($user->name(), $userQueryResponse->name());
        $this->assertEquals($user->email(), $userQueryResponse->email());
        $this->assertEquals($user->notes(), $userQueryResponse->notes());
    }

    public function testHandleUserNotFound() {
        $id = new ID(1);

        $repoMock = $this->createMock(UserQueryReadModel::class);
        $repoMock
            ->expects($this->once())
            ->method('find')
            ->with($id)
            ->willReturn(null);

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->once())
            ->method('log')
            ->with("User with ID 1 not found");

        $handler = new UserQueryHandler($repoMock, $loggerMock);

        $this->expectException(UserNotFoundException::class);
        $handler->handle(new UserQuery($id));
    }
}
