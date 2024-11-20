<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\App\Services\UserInfoVerifier;
use DmitriySmotrov\Interview\Domain\User\DateTime;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\User;

/**
 * Create user command handler.
 *
 * This handler creates a new user based on the provided command.
 * It will also verify the user name and email before creating the user.
 *
 * @package DmitriySmotrov\Interview\App\Commands
 */
class CreateUserCommandHandler {
    private Repository $users;
    private Logger $logger;
    private UserInfoVerifier $userInfoVerifier;

    public function __construct(
        Repository       $users,
        UserInfoVerifier $userInfoVerifier,
        Logger           $logger,
    ) {
        $this->users = $users;
        $this->logger = $logger;
        $this->userInfoVerifier = $userInfoVerifier;
    }

    public function handle(CreateUserCommand $command): ID {
        try {
            $this->userInfoVerifier->verify($command->name(), $command->email());
        } catch (UserNameContainsStopWordException $e) {
            $this->logger->log("Attempt to create user with stop word in name {$command->name()->toString()}");
            throw $e;
        } catch (UserEmailUntrustedDomainException $e) {
            $this->logger->log("Attempt to create user with untrusted domain {$command->email()->toString()}");
            throw $e;
        }
        $user = User::new(
            $command->name(),
            $command->email(),
            DateTime::now(),
            $command->notes()
        );
        $this->users->create($user);

        return $user->id();
    }
}
