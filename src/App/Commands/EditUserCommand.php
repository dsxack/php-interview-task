<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;

/**
 * Command to edit a user.
 *
 * @package DmitriySmotrov\Interview\App\Commands
 */
class EditUserCommand {
    private ID $id;
    private Name $name;
    private Email $email;
    private ?Notes $notes;

    public function __construct(
        ID     $id,
        Name   $name,
        Email  $email,
        ?Notes $notes,
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->notes = $notes;
    }

    public function id(): ID {
        return $this->id;
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
