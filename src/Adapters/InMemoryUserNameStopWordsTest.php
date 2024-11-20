<?php

namespace DmitriySmotrov\Interview\Adapters;

use DmitriySmotrov\Interview\Domain\User\Name;
use PHPUnit\Framework\TestCase;

class InMemoryUserNameStopWordsTest extends TestCase {
    public function testContains() {
        $stopWords = ['stop', 'word'];
        $stopWordsAdapter = new InMemoryUserNameStopWords($stopWords);
        $this->assertTrue($stopWordsAdapter->contains(new Name('johndoe1stop')));
        $this->assertTrue($stopWordsAdapter->contains(new Name('johndoe1word')));
        $this->assertTrue($stopWordsAdapter->contains(new Name('johndoe1stopword')));
        $this->assertFalse($stopWordsAdapter->contains(new Name('johndoe1')));
    }
}