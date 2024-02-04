<?php

namespace App\Interfaces;

use PDO;
use PDOStatement;

interface IDbHandler
{
    public function getPdo(): PDO;
    public function raw(string $sql): PDOStatement;
}
