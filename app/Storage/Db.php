<?php

namespace App\Storage;

use App\Helpers\RequestHandler;
use PDO;
use PDOException;
use PDOStatement;

class Db
{
    use TSingleton;

    private static PDO $pdo;

    private function __construct()
    {
        $db = [
            'dsn'      =>'mysql:host=localhost;dbname=nk;charset=utf8',
            'user'     => 'root',
            'password' => ''
        ];
        
        try {
            self::$pdo = new PDO(
                $db['dsn'],
                $db['user'],
                $db['password']
            );

            self::$pdo->setAttribute(
                PDO::ATTR_ERRMODE,
                PDO::ERRMODE_EXCEPTION
            );
        } catch (PDOException $e) {
            RequestHandler::doResponse('error', $e->getMessage(), 500);
            die();
        }
    }

    public static function getPdo()
    {
        return self::$pdo;
    }

    public static function raw(string $sql): PDOStatement
    {
        return self::$pdo->prepare($sql);
    }
}
