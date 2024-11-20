<?php

namespace DmitriySmotrov\Interview\Adapters;

use DmitriySmotrov\Interview\App\Queries\UserQueryReadModel;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\User;
use DmitriySmotrov\Interview\Domain\User\UserNotFoundException;
use Exception;

/**
 * InMemoryUserRepository it is a Repository implementation that stores users in memory.
 *
 * @package DmitriySmotrov\Interview\Adapters
 */
class InMemoryUserRepository implements Repository, UserQueryReadModel {
    /** @var User[] */
    private array $users = [];
    private int $nextId = 1;

    /**
     * Creates a new user.
     *
     * @param User $user
     * @return void
     * @throws Exception
     */
    public function create(User $user): void {
        foreach ($this->users as $existingUser) {
            if ($existingUser->email()->equals($user->email())) {
                throw new Exception("User with email {$user->email()->toString()} already exists");
            }
            if ($existingUser->name()->equals($user->name())) {
                throw new Exception("User with name {$user->name()->toString()} already exists");
            }
        }
        $this->users[$this->nextId] = $user;
        $id = new ID($this->nextId);
        $user->setID($id);
        $this->nextId++;
    }

    /**
     * Finds a user by ID.
     *
     * @param ID $id
     * @return User|null
     */
    public function find(ID $id): ?User {
        return $this->users[$id->toInteger()] ?? null;
    }

    /**
     * Updates a user.
     *
     * @param ID $id
     * @param callable(User): void $callback
     * @return void
     * @throws Exception
     */
    public function update(ID $id, callable $callback): void {
        $user = $this->users[$id->toInteger()] ?? null;
        if ($user === null) {
            throw new UserNotFoundException("User with ID {$id->toInteger()} not found");
        }
        $clone = clone $user;
        $callback($clone);
        foreach ($this->users as $existingUser) {
            if ($existingUser->email()->equals($clone->email()) && $existingUser->id() !== $clone->id()) {
                throw new Exception("User with email {$clone->email()->toString()} already exists");
            }
            if ($existingUser->name()->equals($clone->name()) && $existingUser->id() !== $clone->id()) {
                throw new Exception("User with name {$clone->name()->toString()} already exists");
            }
        }
        $this->users[$id->toInteger()] = $clone;
    }
}

