<?php

namespace DmitriySmotrov\Interview\App\Queries;

use DmitriySmotrov\Interview\Domain\User\ID;
use DmitriySmotrov\Interview\Domain\User\User;

/**
 * Interface UserQueryReadModel
 *
 * This interface is used to define the methods that will be used to query the User read model.
 *
 * @package DmitriySmotrov\Interview\App\Queries
 */
interface UserQueryReadModel {
    public function find(ID $id): ?User;
}