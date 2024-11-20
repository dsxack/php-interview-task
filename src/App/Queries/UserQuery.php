<?php

namespace DmitriySmotrov\Interview\App\Queries;

use DmitriySmotrov\Interview\Domain\User\ID;

/**
 * Query to get a user by ID.
 *
 * @package DmitriySmotrov\Interview\App\Queries
 */
class UserQuery {
    private ID $id;

    public function __construct(ID $id) {
        $this->id = $id;
    }

    public function id(): ID {
        return $this->id;
    }
}
