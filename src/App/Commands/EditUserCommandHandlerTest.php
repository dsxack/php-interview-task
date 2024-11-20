<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\App\Services\UserInfoVerifier;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\Timestamps;
use DmitriySmotrov\Interview\Domain\User\User;
use PHPUnit\Framework\TestCase;

class EditUserCommandHandlerTest extends TestCase {
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
                $this->assertEquals($user->id()->toInteger(), $id->toInteger());
                $callback($user);
                $this->assertEquals('johndoe2', $user->name()->toString());
                $this->assertEquals('johndoe2@domain.com', $user->email()->toString());
                $this->assertEquals('test new notes', $user->notes()->toString());
            });

        $userInfoVerifierMock = $this->createMock(UserInfoVerifier::class);
        $userInfoVerifierMock
            ->expects($this->once())
            ->method('verify')
            ->with(new Name('johndoe2'), new Email('johndoe2@domain.com'));

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->once())
            ->method('log')
            ->with("Trying to edit User", [
                "id" => 1,
                "old_name" => 'johndoe1',
                "old_email" => 'johndoe1@domain.com',
                "old_notes" => 'test notes',
                "new_name" => 'johndoe2',
                "new_email" => 'johndoe2@domain.com',
                "new_notes" => 'test new notes',
            ]);

        $handler = new EditUserCommandHandler($repoMock, $userInfoVerifierMock, $loggerMock);
        $handler->handle(new EditUserCommand(
            $user->id(),
            new Name('johndoe2'),
            new Email('johndoe2@domain.com'),
            new Notes('test new notes'),
        ));
    }

    public function testHandleWithStopWordInUserName() {
        $repoMock = $this->createMock(Repository::class);

        $userInfoVerifierMock = $this->createMock(UserInfoVerifier::class);
        $userInfoVerifierMock
            ->expects($this->once())
            ->method('verify')
            ->willThrowException(new UserNameContainsStopWordException());

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->once())
            ->method('log')
            ->with("Attempt to edit user with stop word in name johndoe2");

        $handler = new EditUserCommandHandler($repoMock, $userInfoVerifierMock, $loggerMock);

        $this->expectException(UserNameContainsStopWordException::class);
        $handler->handle(new EditUserCommand(
            new ID(1),
            new Name('johndoe2'),
            new Email('johndoe2@domain.com'),
            new Notes('test new notes'),
        ));
    }

    public function testHandleWithUntrustedDomain() {
        $repoMock = $this->createMock(Repository::class);

        $userInfoVerifierMock = $this->createMock(UserInfoVerifier::class);
        $userInfoVerifierMock
            ->expects($this->once())
            ->method('verify')
            ->willThrowException(new UserEmailUntrustedDomainException());

        $loggerMock = $this->createMock(Logger::class);
        $loggerMock
            ->expects($this->once())
            ->method('log')
            ->with("Attempt to edit user email with untrusted domain johndoe2@domain.com");

        $handler = new EditUserCommandHandler($repoMock, $userInfoVerifierMock, $loggerMock);

        $this->expectException(UserEmailUntrustedDomainException::class);
        $handler->handle(new EditUserCommand(
            new ID(1),
            new Name('johndoe2'),
            new Email('johndoe2@domain.com'),
            new Notes('test new notes'),
        ));
    }
}