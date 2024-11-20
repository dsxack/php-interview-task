<?php

use DmitriySmotrov\Interview\App\Commands\CreateUserCommand;
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


$module->editUser(new EditUserCommand(
    $userId,
    new Name('johndoe1234'),
    new Email('johndoe1234@mail.com'),
    new Notes('Some notes 2'),
));

echo "User edited with ID: {$userId->toInteger()}\n";