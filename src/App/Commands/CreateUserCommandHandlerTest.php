<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\App\Services\UserInfoVerifier;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\Repository;
use PHPUnit\Framework\TestCase;

class CreateUserCommandHandlerTest extends TestCase {
    public function testHandle() {
        $repoMock = $this->createMock(Repository::class);
        $repoMock
            ->expects($this->once())
            ->method('create')
            ->with($this->callback(function ($user) {
                $this->assertEquals('johndoe1', $user->name()->toString());
                $this->assertEquals('johndoe1@domain.com', $user->email()->toString());
                $this->assertEquals('test notes', $user->notes()->toString());
                $user->setID(new ID(5));
                return true;
            }));

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->never())
            ->method('log');

        $userInfoVerifierMock = $this->createMock(UserInfoVerifier::class);
        $userInfoVerifierMock
            ->expects($this->once())
            ->method('verify')
            ->with(new Name('johndoe1'), new Email('johndoe1@domain.com'));

        $handler = new CreateUserCommandHandler($repoMock, $userInfoVerifierMock, $loggerMock);
        $resultId = $handler->handle(new CreateUserCommand(
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
            new Notes('test notes'),
        ));

        $this->assertEquals(5, $resultId->toInteger());
    }

    public function testHandleWithStopWordInUserName() {
        $repoMock = $this->createMock(Repository::class);
        $repoMock
            ->expects($this->never())
            ->method('create');

        $userInfoVerifierMock = $this->createMock(UserInfoVerifier::class);
        $userInfoVerifierMock
            ->expects($this->once())
            ->method('verify')
            ->willThrowException(new UserNameContainsStopWordException());

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->once())
            ->method('log')
            ->with("Attempt to create user with stop word in name johndoe1StopWord");

        $handler = new CreateUserCommandHandler($repoMock, $userInfoVerifierMock, $loggerMock);

        $this->expectException(UserNameContainsStopWordException::class);
        $handler->handle(new CreateUserCommand(
            new Name('johndoe1StopWord'),
            new Email('johndoe1@domain.com'),
            new Notes('test notes'),
        ));
    }

    public function testHandleWithUntrustedDomain() {
        $repoMock = $this->createMock(Repository::class);
        $repoMock
            ->expects($this->never())
            ->method('create');

        $userInfoVerifierMock = $this->createMock(UserInfoVerifier::class);
        $userInfoVerifierMock
            ->expects($this->once())
            ->method('verify')
            ->willThrowException(new UserEmailUntrustedDomainException());

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->once())
            ->method('log')
            ->with("Attempt to create user with untrusted domain johndoe1@domain.com");

        $handler = new CreateUserCommandHandler($repoMock, $userInfoVerifierMock, $loggerMock);

        $this->expectException(UserEmailUntrustedDomainException::class);
        $handler->handle(new CreateUserCommand(
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
            new Notes('test notes'),
        ));
    }
}
