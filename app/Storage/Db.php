<?php

namespace App\Storage;

use App\Helpers\RequestHandler;
use App\Interfaces\IDbHandler;
use PDO;
use PDOException;
use PDOStatement;

class Db implements IDbHandler
{
    private PDO $pdo;

    public function __construct(array $config)
    {
        $dsn      = $config['dsn'];
        $username = $config['user'];
        $password = $config['password'];

        try {
            $this->pdo = new PDO($dsn, $username, $password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            RequestHandler::doResponse('error', $e->getMessage(), 500);
            die();
        }
    }

    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    public function raw(string $sql): PDOStatement
    {
        return $this->pdo->prepare($sql);
    }
}
