<?php

namespace DmitriySmotrov\Interview\Adapters;

use DmitriySmotrov\Interview\App\Queries\UserQueryReadModel;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\Timestamps;
use DmitriySmotrov\Interview\Domain\User\User;
use DmitriySmotrov\Interview\Domain\User\UserNotFoundException;
use Exception;
use PDO;

/**
 * SQLUserRepository it is a Repository implementation that stores users in a SQL database.
 *
 * This implementation uses PDO to interact with the database.
 * SQLite is used for testing purposes, so the implementation uses a different SQL query for locking rows.
 *
 * @package DmitriySmotrov\Interview\Adapters
 */
class SQLUserRepository implements Repository, UserQueryReadModel {

    private PDO $pdo;
    private bool $sqlite;

    /**
     * SQLUserRepository constructor.
     * @param PDO $pdo
     * @param bool $sqlite
     */
    public function __construct(PDO $pdo, bool $sqlite) {
        $this->pdo = $pdo;
        $this->sqlite = $sqlite;
    }

    /**
     * Finds a user by ID.
     *
     * @param ID $id
     * @return User|null
     */
    public function find(ID $id): ?User {
        return $this->select($id, false);
    }

    /**
     * Creates a new user.
     *
     * @param User $user
     * @return void
     */
    public function create(User $user): void {
        $stmt = $this->pdo->prepare('INSERT INTO users (name, email, created, deleted, notes) VALUES (:name, :email, :created, :deleted, :notes)');
        $stmt->execute([
            'name' => $user->name()->toString(),
            'email' => $user->email()->toString(),
            'created' => $user->timestamps()->createdAt()->toString(),
            'deleted' => $user->timestamps()->deletedAt()?->toString(),
            'notes' => $user->notes()?->toString(),
        ]);
        $id = new ID($this->pdo->lastInsertId());
        $user->setID($id);
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
        $this->pdo->beginTransaction();
        try {
            $user = $this->select($id, true);
            if ($user === null) {
                throw new UserNotFoundException("User with ID {$id->toInteger()} not found");
            }
            $callback($user);
            $stmt = $this->pdo->prepare('UPDATE users SET name = :name, email = :email, created = :created, deleted = :deleted, notes = :notes WHERE id = :id');
            $stmt->execute([
                'id' => $user->id()?->toInteger(),
                'name' => $user->name()?->toString(),
                'email' => $user->email()?->toString(),
                'created' => $user->timestamps()->createdAt()->toString(),
                'deleted' => $user->timestamps()->deletedAt()?->toString(),
                'notes' => $user->notes()?->toString(),
            ]);
            $this->pdo->commit();
        } catch (Exception $e) {
            $this->pdo->rollBack();
            throw $e;
        }
    }

    private function select(ID $id, bool $forUpdate): ?User {
        $query = 'SELECT * FROM users WHERE id = :id';
        if ($forUpdate && !$this->sqlite) {
            $query .= ' FOR UPDATE';
        }
        $stmt = $this->pdo->prepare($query);
        $stmt->execute(['id' => $id->toInteger()]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($data === false) {
            return null;
        }
        $name = new Name($data['name']);
        $email = new Email($data['email']);
        $ts = Timestamps::fromStorage(
            DateTime::fromString($data['created']),
            $data['deleted'] ? DateTime::fromString($data['deleted']): null,
        );
        $notes = null;
        if ($data['notes'] !== null) {
            $notes = new Notes($data['notes']);
        }
        return User::fromStorage(
            $id,
            $name,
            $email,
            $ts,
            $notes,
        );
    }
}
