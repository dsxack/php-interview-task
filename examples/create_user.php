<?php

use DmitriySmotrov\Interview\App\Commands\CreateUserCommand;
use DmitriySmotrov\Interview\Domain\User\Email;
use DmitriySmotrov\Interview\Domain\User\Name;
use DmitriySmotrov\Interview\Module;

/** @var Module $module */
$module = require __DIR__ . '/module.php';
$name = new Name('johndoe123');
$email = new Email('john.doe@mail.com');
$userId = $module->createUser(new CreateUserCommand($name, $email, null));

echo "User created with ID: {$userId->toInteger()}\n";
