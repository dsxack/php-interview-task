<?php

namespace DmitriySmotrov\Interview\Domain\User;

/**
 * Class User
 *
 * Represents a user.
 *
 * @package DmitriySmotrov\Interview\Domain\User
 */
class User {
    private ?ID $id;
    private Name $name;
    private Email $email;
    private Timestamps $timestamps;
    private ?Notes $notes;

    /**
     * User constructor.
     *
     * @param ?ID $id
     * @param Name $name
     * @param Email $email
     * @param Timestamps $timestamps
     * @param Notes|null $notes
     */
    private function __construct(
        ?ID        $id,
        Name       $name,
        Email      $email,
        Timestamps $timestamps,
        ?Notes     $notes
    ) {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->notes = $notes;
        $this->timestamps = $timestamps;
    }

    public static function new(
        Name     $name,
        Email    $email,
        DateTime $now,
        ?Notes   $notes,
    ): User {
        return new User(
            null,
            $name,
            $email,
            Timestamps::new($now),
            $notes
        );
    }

    public static function fromStorage(
        ID         $param,
        Name      $name,
        Email     $email,
        Timestamps $timestamps,
        ?Notes     $notes,
    ): User {
        return new User(
            $param,
            $name,
            $email,
            $timestamps,
            $notes,
        );

    }

    public function id(): ?ID {
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

    public function timestamps(): Timestamps {
        return $this->timestamps;
    }

    public function delete(DateTime $now): void {
        $this->timestamps = $this->timestamps->delete($now);
    }

    public function edit(Name $name, Email $email, ?Notes $notes): void {
        $this->name = $name;
        $this->email = $email;
        $this->notes = $notes;
    }

    public function setID(ID $id): void {
        if ($this->id !== null) {
            throw new UserAlreadyHasIDException('ID is already set');
        }
        $this->id = $id;
    }
}
