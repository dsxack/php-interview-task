<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\Adapters\FileLogger;
use DmitriySmotrov\Interview\Adapters\InMemoryUserEmailUntrustedDomains;
use DmitriySmotrov\Interview\Adapters\InMemoryUserNameStopWords;
use DmitriySmotrov\Interview\Adapters\InMemoryUserRepository;
use DmitriySmotrov\Interview\App\Services\UserInfoVerifier;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Domain;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\User;
use PHPUnit\Framework\TestCase;

class EditUserCommandHandlerTest extends TestCase {
    public function testHandle() {
        $users = new InMemoryUserRepository();
        $user = $this->newTestUser();
        $users->create($user);

        $handler = $this->newHandler($users, [], []);
        $handler->handle(new EditUserCommand(
            $user->id(),
            new Name('johndoe2'),
            new Email('johndoe2@domain.com'),
            new Notes('test new notes'),
        ));

        $updatedUser = $users->find($user->id());
        $this->assertEquals('johndoe2', $updatedUser->name()->toString());
        $this->assertEquals('johndoe2@domain.com', $updatedUser->email()->toString());
        $this->assertEquals('test new notes', $updatedUser->notes()->toString());
    }

    public function testHandleWithStopWordInUserName() {
        $users = new InMemoryUserRepository();
        $user = $this->newTestUser();
        $users->create($user);
        $handler = $this->newHandler(new InMemoryUserRepository(), ['stopWord'], []);

        $this->expectException(UserNameContainsStopWordException::class);
        $handler->handle(new EditUserCommand(
            $user->id(),
            new Name('johndoe1StopWord'),
            new Email('johndoe1@domain.com'),
            new Notes('test notes'),
        ));
    }

    public function testHandleWithUntrustedDomain() {
        $users = new InMemoryUserRepository();
        $user = $this->newTestUser();
        $users->create($user);

        $handler = $this->newHandler(new InMemoryUserRepository(), [], [new Domain('domain.com')]);

        $this->expectException(UserEmailUntrustedDomainException::class);
        $handler->handle(new EditUserCommand(
            $user->id(),
            new Name('johndoe1StopWord'),
            new Email('johndoe1@domain.com'),
            new Notes('test notes'),
        ));
    }

    private function newHandler(Repository $users, array $stopWords, array $untrustedDomains): EditUserCommandHandler {
        $logFile = fopen('php://memory', 'w+');
        $logger = new FileLogger($logFile);
        $stopWords = new InMemoryUserNameStopWords($stopWords);
        $untrustedDomain = new InMemoryUserEmailUntrustedDomains($untrustedDomains);
        $userInfoVerifier = new UserInfoVerifier($untrustedDomain, $stopWords);

        return new EditUserCommandHandler($users, $userInfoVerifier, $logger);
    }

    public function newTestUser(): User {
        return User::new(
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
            DateTime::now(),
            new Notes('test notes'),
        );
    }
}