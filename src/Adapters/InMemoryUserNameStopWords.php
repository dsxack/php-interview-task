<?php

namespace DmitriySmotrov\Interview\Adapters;

use DmitriySmotrov\Interview\App\Services\UserNameStopWords;
use DmitriySmotrov\Interview\Domain\User\Name;

/**
 * InMemoryUserNameStopWords it is a UserNameStopWords implementation that stores stop words in memory.
 *
 * @package DmitriySmotrov\Interview\Adapters
 */
class InMemoryUserNameStopWords implements UserNameStopWords {
    /** @var string[] */
    private array $stopWords;

    /**
     * InMemoryUserNameStopWords constructor.
     * @param string[] $stopWords
     */
    public function __construct(array $stopWords) {
        $this->stopWords = $stopWords;
    }

    /**
     * Checks if the username contains stop words.
     *
     * @param Name $username
     * @return bool
     */
    public function contains(Name $username): bool {
        foreach ($this->stopWords as $stopWord) {
            if ($username->contains($stopWord)) {
                return true;
            }
        }
        return false;
    }
}
