<?php

namespace DmitriySmotrov\Interview\App\Services;

use DmitriySmotrov\Interview\Adapters\InMemoryUserEmailUntrustedDomains;
use DmitriySmotrov\Interview\Adapters\InMemoryUserNameStopWords;
use DmitriySmotrov\Interview\App\Commands\UserEmailUntrustedDomainException;
use DmitriySmotrov\Interview\App\Commands\UserNameContainsStopWordException;
use DmitriySmotrov\Interview\Domain\User\Domain;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use PHPUnit\Framework\TestCase;

class UserInfoVerifierTest extends TestCase {
    public function testVerify() {
        $verifier = new UserInfoVerifier(
            new InMemoryUserEmailUntrustedDomains([new Domain('example.com')]),
            new InMemoryUserNameStopWords(['stopWord']),
        );
        $verifier->verify(new Name('johndoe1'), new Email('johndoe1@domain.com'));
        $this->expectNotToPerformAssertions();
    }

    public function testVerifyWithUntrustedDomain() {
        $verifier = new UserInfoVerifier(
            new InMemoryUserEmailUntrustedDomains([new Domain('example.com')]),
            new InMemoryUserNameStopWords(['stopWord']),
        );
        $this->expectException(UserEmailUntrustedDomainException::class);
        $verifier->verify(new Name('johndoe1'), new Email('johndoe1@example.com'));
    }

    public function testVerifyWithStopWordInUserName() {
        $verifier = new UserInfoVerifier(
            new InMemoryUserEmailUntrustedDomains([new Domain('example.com')]),
            new InMemoryUserNameStopWords(['stopWord']),
        );
        $this->expectException(UserNameContainsStopWordException::class);
        $verifier->verify(new Name('johndoe1StopWord'), new Email('johndoe1@domain.com'));
    }
}
