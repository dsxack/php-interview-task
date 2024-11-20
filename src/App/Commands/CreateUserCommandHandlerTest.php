<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\Adapters\InMemoryUserEmailUntrustedDomains;
use DmitriySmotrov\Interview\Adapters\InMemoryUserNameStopWords;
use DmitriySmotrov\Interview\App\Services\UserInfoVerifier;
use DmitriySmotrov\Interview\Domain\User\Domain;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Adapters\InMemoryUserRepository;
use DmitriySmotrov\Interview\Adapters\FileLogger;
use DmitriySmotrov\Interview\Domain\User\Repository;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerTest extends TestCase {
    public function testHandle() {
        $users = new InMemoryUserRepository();
        $handler = $this->newHandler($users, [], []);
        $id = $handler->handle(new CreateUserCommand(
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
            new Notes('test notes'),
        ));

        $this->assertEquals(1, $id->toInteger());

        $user = $users->find($id);
        $this->assertEquals('johndoe1', $user->name()->toString());
        $this->assertEquals('johndoe1@domain.com', $user->email()->toString());
        $this->assertEquals('test notes', $user->notes()->toString());
    }

    public function testHandleWithStopWordInUserName() {
        $handler = $this->newHandler(new InMemoryUserRepository(), ['stopWord'], []);

        $this->expectException(UserNameContainsStopWordException::class);
        $handler->handle(new CreateUserCommand(
            new Name('johndoe1StopWord'),
            new Email('johndoe1@domain.com'),
            new Notes('test notes'),
        ));
    }

    public function testHandleWithUntrustedDomain() {
        $handler = $this->newHandler(new InMemoryUserRepository(), [], [new Domain('domain.com')]);

        $this->expectException(UserEmailUntrustedDomainException::class);
        $handler->handle(new CreateUserCommand(
            new Name('johndoe1StopWord'),
            new Email('johndoe1@domain.com'),
            new Notes('test notes'),
        ));
    }

    private function newHandler(Repository $users, array $stopWords, array $untrustedDomains): CreateUserCommandHandler {
        $logFile = fopen('php://memory', 'w+');
        $logger = new FileLogger($logFile);
        $stopWords = new InMemoryUserNameStopWords($stopWords);
        $untrustedDomain = new InMemoryUserEmailUntrustedDomains($untrustedDomains);
        $userInfoVerifier = new UserInfoVerifier($untrustedDomain, $stopWords);

        return new CreateUserCommandHandler($users, $userInfoVerifier, $logger);
    }
}