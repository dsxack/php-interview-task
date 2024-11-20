<?php

namespace DmitriySmotrov\Interview\App\Services;

use DmitriySmotrov\Interview\App\Commands\UserEmailUntrustedDomainException;
use DmitriySmotrov\Interview\App\Commands\UserNameContainsStopWordException;
use DmitriySmotrov\Interview\Domain\User\Domain;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use PHPUnit\Framework\TestCase;

class UserInfoVerifierTest extends TestCase {
    public function testVerify() {
        $untrustedDomains = $this->createMock(UserEmailUntrustedDomains::class);
        $untrustedDomains
            ->expects($this->once())
            ->method('isUntrusted')
            ->with(new Domain('domain.com'));

        $stopWords = $this->createMock(UserNameStopWords::class);
        $stopWords
            ->expects($this->once())
            ->method('contains')
            ->with(new Name('johndoe1'));

        $verifier = new UserInfoVerifier($untrustedDomains, $stopWords);
        $verifier->verify(
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
        );
    }

    public function testVerifyWithUntrustedDomain() {
        $untrustedDomains = $this->createMock(UserEmailUntrustedDomains::class);
        $untrustedDomains
            ->expects($this->once())
            ->method('isUntrusted')
            ->with(new Domain('domain.com'))
            ->willThrowException(new UserEmailUntrustedDomainException());

        $stopWords = $this->createMock(UserNameStopWords::class);

        $verifier = new UserInfoVerifier($untrustedDomains, $stopWords);

        $this->expectException(UserEmailUntrustedDomainException::class);
        $verifier->verify(
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
        );
    }

    public function testVerifyWithStopWordInUserName() {
        $untrustedDomains = $this->createMock(UserEmailUntrustedDomains::class);

        $stopWords = $this->createMock(UserNameStopWords::class);
        $stopWords
            ->expects($this->once())
            ->method('contains')
            ->with(new Name('johndoe1'))
            ->willThrowException(new UserNameContainsStopWordException());

        $verifier = new UserInfoVerifier($untrustedDomains, $stopWords);

        $this->expectException(UserNameContainsStopWordException::class);
        $verifier->verify(
            new Name('johndoe1'),
            new Email('johndoe1@domain.com'),
        );
    }
}
