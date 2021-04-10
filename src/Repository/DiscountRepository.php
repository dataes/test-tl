<?php

declare(strict_types=1);

namespace App\Repository;

final class DiscountRepository
{
    protected \PDO $database;

    public function __construct(\PDO $database)
    {
        $this->database = $database;
    }

    protected function getDb(): \PDO
    {
        return $this->database;
    }

}