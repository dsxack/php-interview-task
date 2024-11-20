<?php

namespace DmitriySmotrov\Interview\App\Queries;

use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\Domain\User\UserNotFoundException;

/**
 * Handler for the UserQuery.
 *
 * This handler is responsible for finding a user by ID and returning it.
 *
 * @package DmitriySmotrov\Interview\App\Queries
 */
class UserQueryHandler {
    private UserQueryReadModel $readModel;
    private Logger $logger;

    public function __construct(UserQueryReadModel $readModel, Logger $logger) {
        $this->readModel = $readModel;
        $this->logger = $logger;
    }

    public function handle(UserQuery $query): UserQueryResponse {
        $user = $this->readModel->find($query->id());
        if ($user === null) {
            $this->logger->log("User with ID {$query->id()->toInteger()} not found");
            throw new UserNotFoundException("User with ID {$query->id()->toInteger()} not found");
        }
        return new UserQueryResponse(
            $user->id(),
            $user->name(),
            $user->email(),
            $user->notes(),
        );
    }
}