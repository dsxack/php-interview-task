<?php

use DmitriySmotrov\Interview\App\Commands\CreateUserCommand;
use DmitriySmotrov\Interview\App\Commands\DeleteUserCommand;
use DmitriySmotrov\Interview\App\Commands\EditUserCommand;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Domain\User\Notes;
use DmitriySmotrov\Interview\Module;

/** @var Module $module */
$module = require __DIR__ . '/module.php';
$userId = $module->createUser(new CreateUserCommand(
    new Name('johndoe123'),
    new Email('john.doe@mail.com'),
    new Notes('Some notes'),
));

echo "User created with ID: {$userId->toInteger()}\n";


$module->deleteUser(new DeleteUserCommand($userId));

echo "User deleted with ID: {$userId->toInteger()}\n";
