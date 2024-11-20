<?php

namespace DmitriySmotrov\Interview\App\Services;

use DmitriySmotrov\Interview\App\Commands\UserEmailUntrustedDomainException;
use DmitriySmotrov\Interview\App\Commands\UserNameContainsStopWordException;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;

/**
 * Class UserInfoVerifier
 *
 * Verifies that user name and email are valid.
 *
 * @package DmitriySmotrov\Interview\App\Services
 */
class UserInfoVerifier {
    private UserEmailUntrustedDomains $untrustedDomains;
    private UserNameStopWords $stopWords;

    public function __construct(UserEmailUntrustedDomains $untrustedDomains, UserNameStopWords $stopWords) {
        $this->untrustedDomains = $untrustedDomains;
        $this->stopWords = $stopWords;
    }

    public function verify(Name $username, Email $email): void {
        if ($this->stopWords->contains($username)) {
            throw new UserNameContainsStopWordException("Name {$username->toString()} contains stop word");
        }
        if ($this->untrustedDomains->isUntrusted($email->domain())) {
            throw new UserEmailUntrustedDomainException("Domain {$email->domain()->toString()} is untrusted");
        }
    }
}
