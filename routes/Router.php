<?php

use App\Helpers\Parser;
use App\Helpers\RequestHandler;

class Router
{
    private static array $handler = [];

    public static function post(string $route, callable $handler): void
    {
        self::$handler[] = ['POST', $route, $handler];
    }

    public static function get(string $route, callable $handler): void
    {
        self::$handler[] = ['GET', $route, $handler];
    }

    public static function put(string $route, callable $handler): void
    {
        self::$handler[] = ['PUT', Parser::getPatternFromRoute($route), $handler];
    }

    public static function delete(string $route, callable $handler): void
    {
        self::$handler[] = ['DELETE', Parser::getPatternFromRoute($route), $handler];
    }

    public static function run(): void
    {
        $uri    = $_SERVER['REQUEST_URI'];
        $method = $_SERVER['REQUEST_METHOD'];

        $uriParts = explode('?', $uri);
        $path = $uriParts[0];
        $queryParams = isset($uriParts[1]) ? $uriParts[1] : '';

        foreach (self::$handler as $item) {
            [$handlerMethod, $route, $handler] = $item;

            if (
                $method === $handlerMethod
                && Parser::isRegex($route)
                && preg_match($route, $uri, $matches)
            ) {
                $id = (int) $matches[1];

                echo $handler($id);
                return;
            }

            if (
                $method === $handlerMethod
                && $route === $path
            ) {
                $params = Parser::parseQueryParams($queryParams);
                echo $handler($params);
                return;
            }
        }

        RequestHandler::doResponse('error', 'No route', 404);
    }
}
