<?php

namespace DmitriySmotrov\Interview\Adapters;

use DmitriySmotrov\Interview\Domain\User\Domain;
use PHPUnit\Framework\TestCase;

class InMemoryUserEmailUntrustedDomainsTest extends TestCase {
    public function testIsUntrusted() {
        $untrustedDomains = new InMemoryUserEmailUntrustedDomains([
            new Domain('example.com'),
        ]);
        $this->assertTrue($untrustedDomains->isUntrusted(new Domain('example.com')));
        $this->assertFalse($untrustedDomains->isUntrusted(new Domain('example.org')));
    }
}
