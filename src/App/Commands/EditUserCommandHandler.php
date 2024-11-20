<?php

namespace DmitriySmotrov\Interview\App\Commands;

use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\App\Services\UserInfoVerifier;
use DmitriySmotrov\Interview\Domain\User\Repository;
use DmitriySmotrov\Interview\Domain\User\User;

/**
 * Handler for the command to edit a user.
 *
 * This handler will update the user in the repository and log the event.
 * It will also verify the user name and email before updating the user.
 *
 * @package DmitriySmotrov\Interview\App\Commands
 */
class EditUserCommandHandler {
    private Repository $users;
    private Logger $logger;
    private UserInfoVerifier $userInfoVerifier;

    public function __construct(
        Repository       $users,
        UserInfoVerifier $userInfoVerifier,
        Logger           $logger,
    ) {
        $this->users = $users;
        $this->userInfoVerifier = $userInfoVerifier;
        $this->logger = $logger;
    }

    public function handle(EditUserCommand $command): void {
        try {
            $this->userInfoVerifier->verify($command->name(), $command->email());
        } catch (UserNameContainsStopWordException $e) {
            $this->logger->log("Attempt to edit user with stop word in name {$command->name()->toString()}");
            throw $e;
        } catch (UserEmailUntrustedDomainException $e) {
            $this->logger->log("Attempt to edit user email with untrusted domain {$command->email()->toString()}");
            throw $e;
        }
        $this->users->update($command->id(), function (User $user) use ($command): void {
            $this->logger->log("Trying to edit User", [
                "id" => $command->id()->toInteger(),
                "old_name" => $user->name()->toString(),
                "old_email" => $user->email()->toString(),
                "old_notes" => $user->notes()->toString(),
                "new_name" => $command->name()->toString(),
                "new_email" => $command->email()->toString(),
                "new_notes" => $command->notes()->toString(),
            ]);
            $user->edit(
                $command->name(),
                $command->email(),
                $command->notes(),
            );
        });
    }
}
