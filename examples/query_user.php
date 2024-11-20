<?php

use DmitriySmotrov\Interview\App\Commands\CreateUserCommand;
use DmitriySmotrov\Interview\App\Queries\UserQuery;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Module;

/** @var Module $module */
$module = require __DIR__ . '/module.php';
$name = new Name('johndoe123');
$email = new Email('john.doe@mail.com');
$userId = $module->createUser(new CreateUserCommand($name, $email, null));

$user = $module->queryUser(new UserQuery($userId));
echo "User ID: {$userId->toInteger()}\n";
echo "User name: {$user->name()->toString()}\n";
echo "User email: {$user->email()->toString()}\n";
