<?php

use DmitriySmotrov\Interview\Adapters\InMemoryUserEmailUntrustedDomains;
use DmitriySmotrov\Interview\Adapters\InMemoryUserNameStopWords;
use DmitriySmotrov\Interview\Adapters\SQLUserRepository;
use DmitriySmotrov\Interview\Adapters\FileLogger;
use DmitriySmotrov\Interview\Module;

require __DIR__ . '/../vendor/autoload.php';

$pdo = new PDO('sqlite::memory:');
$pdo->prepare("
create table users
(
    id integer primary key autoincrement,
    name varchar(64) not null,
    email varchar(256) not null,
    created datetime not null,
    deleted datetime null,
    notes text null
);

create unique index users_email_uindex
    on users (email);

create unique index users_name_uindex
    on users (name);
")->execute();

$repo = new SQLUserRepository($pdo, true);
$logFile = fopen('php://memory', 'w+');
$logger = new FileLogger($logFile);

$stopWords = new InMemoryUserNameStopWords([]);
$untrustedDomains = new InMemoryUserEmailUntrustedDomains([]);

$module = new Module($repo, $repo, $untrustedDomains, $stopWords, $logger);

return $module;