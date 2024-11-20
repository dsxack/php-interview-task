<?php

namespace DmitriySmotrov\Interview\App\Queries;

use DmitriySmotrov\Interview\Adapters\FileLogger;
use DmitriySmotrov\Interview\Adapters\InMemoryUserRepository;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\User;
use PHPUnit\Framework\TestCase;

class UserQueryHandlerTest extends TestCase {
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
        $userQueryResponse = $handler->handle(new UserQuery($user->id()));

        $this->assertEquals($user->id(), $userQueryResponse->id());
        $this->assertEquals($user->name(), $userQueryResponse->name());
        $this->assertEquals($user->email(), $userQueryResponse->email());
        $this->assertEquals($user->notes(), $userQueryResponse->notes());
    }

    private function newHandler(UserQueryReadModel $users): UserQueryHandler {
        $logFile = fopen('php://memory', 'w+');
        $logger = new FileLogger($logFile);
        return new UserQueryHandler($users, $logger);
    }
}