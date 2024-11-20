<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\User;

/**
 * Command handler to delete a user.
 *
 * This handler will delete a user from the repository and log the event.
 *
 * @package DmitriySmotrov\Interview\App\Commands
 */
class DeleteUserCommandHandler {
    private Repository $users;
    private Logger $logger;

    public function __construct(Repository $users, Logger $logger) {
        $this->users = $users;
        $this->logger = $logger;
    }

    public function handle(DeleteUserCommand $command): void {
        $this->users->update($command->id(), function(User $user) use ($command) {
            $user->delete(DateTime::now());
        });
        $this->logger->log("User with ID {$command->id()->toInteger()} deleted");
    }
}
