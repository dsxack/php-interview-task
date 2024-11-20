<?php

namespace DmitriySmotrov\Interview;

use DmitriySmotrov\Interview\App\Commands\CreateUserCommandHandler;
use DmitriySmotrov\Interview\App\Commands\DeleteUserCommand;
use DmitriySmotrov\Interview\App\Commands\DeleteUserCommandHandler;
use DmitriySmotrov\Interview\App\Commands\EditUserCommand;
use DmitriySmotrov\Interview\App\Commands\EditUserCommandHandler;
use DmitriySmotrov\Interview\App\Commands\CreateUserCommand;
use DmitriySmotrov\Interview\App\Queries\UserQuery;
use DmitriySmotrov\Interview\App\Queries\UserQueryHandler;
use DmitriySmotrov\Interview\App\Queries\UserQueryReadModel;
use DmitriySmotrov\Interview\App\Queries\UserQueryResponse;
use DmitriySmotrov\Interview\App\Services\Logger;
use DmitriySmotrov\Interview\App\Services\UserEmailUntrustedDomains;
use DmitriySmotrov\Interview\App\Services\UserInfoVerifier;
use DmitriySmotrov\Interview\App\Services\UserNameStopWords;
use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\Repository;

class Module {
    private CreateUserCommandHandler $createUserCommandHandler;
    private EditUserCommandHandler $editUserCommandHandler;
    private DeleteUserCommandHandler $deleteUserCommandHandler;
    private UserQueryHandler $userQueryHandler;

    public function __construct(
        Repository $repo,
        UserQueryReadModel $readModel,
        UserEmailUntrustedDomains $untrustedDomains,
        UserNameStopWords $stopWords,
        Logger $logger,
    ) {
        $userInfoVerifier = new UserInfoVerifier($untrustedDomains, $stopWords);
        $this->createUserCommandHandler = new CreateUserCommandHandler($repo, $userInfoVerifier, $logger);
        $this->editUserCommandHandler = new EditUserCommandHandler($repo, $userInfoVerifier, $logger);
        $this->deleteUserCommandHandler = new DeleteUserCommandHandler($repo, $logger);
        $this->userQueryHandler = new UserQueryHandler($readModel, $logger);
    }

    public function createUser(CreateUserCommand $command): ID {
        return $this->createUserCommandHandler->handle($command);
    }

    public function editUser(EditUserCommand $command): void {
        $this->editUserCommandHandler->handle($command);
    }

    public function deleteUser(DeleteUserCommand $command): void {
        $this->deleteUserCommandHandler->handle($command);
    }

    public function queryUser(UserQuery $query): UserQueryResponse {
        return $this->userQueryHandler->handle($query);
    }
}
