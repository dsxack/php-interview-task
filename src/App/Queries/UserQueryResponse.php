<?php

namespace DmitriySmotrov\Interview\App\Queries;

use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;

/**
 * Response for the UserQuery.
 *
 * This response is used to return the user data to the client.
 *
 * @package DmitriySmotrov\Interview\App\Queries
 */
class UserQueryResponse {
    private ID $id;
    private Name $name;
    private Email $email;
    private ?Notes $notes;

    public function __construct(
        ID    $id,
        Name  $name,
        Email $email,
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

    public function notes(): Notes {
        return $this->notes;
    }
}