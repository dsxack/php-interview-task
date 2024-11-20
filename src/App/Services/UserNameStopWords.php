<?php

namespace DmitriySmotrov\Interview\App\Services;

use DmitriySmotrov\Interview\Domain\User\Name;

/**
 * Interface UserNameStopWords
 *
 * Interface for checking if a username contains stop words.
 *
 * @package DmitriySmotrov\Interview\App\Services
 */
interface UserNameStopWords {
    public function contains(Name $username): bool;
}
