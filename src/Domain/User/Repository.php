<?php

namespace DmitriySmotrov\Interview\Domain\User;

interface Repository {
    /**
     * @param User $user
     * @return void
     */
    public function create(User $user): void;

    /**
     * @param ID $id
     * @return User|null
     */
    public function find(ID $id): ?User;

    /**
     * @param ID $id
     * @param callable(User): void $callback
     * @return void
     */
    public function update(ID $id, callable $callback): void;
}
