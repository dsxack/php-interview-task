<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;

/**
 * Command to create a new user.
 *
 * @package DmitriySmotrov\Interview\App\Commands
 */
class CreateUserCommand {
    private Name $name;
    private Email $email;
    private ?Notes $notes;

    public function __construct(
        Name   $name,
        Email  $email,
        ?Notes $notes
    ) {
        $this->name = $name;
        $this->email = $email;
        $this->notes = $notes;
    }

    public function name(): Name {
        return $this->name;
    }

    public function email(): Email {
        return $this->email;
    }

    public function notes(): ?Notes {
        return $this->notes;
    }
}
