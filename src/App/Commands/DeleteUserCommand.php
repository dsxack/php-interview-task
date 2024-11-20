<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\Domain\User\ID;

/**
 * Command to delete a user.
 *
 * @package DmitriySmotrov\Interview\App\Commands
 */
class DeleteUserCommand {
    private ID $id;

    public function __construct(ID $id) {
        $this->id = $id;
    }

    public function id(): ID {
        return $this->id;
    }
}
