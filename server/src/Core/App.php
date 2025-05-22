<?php

namespace Src\Core;

class App
{
    protected static Database $database;

    function __construct(Database $database)
    {
        static::$database = $database;

        $config = require_once parseDir(__DIR__) . '/../Config/cors.conf.php';

        header("Access-Control-Allow-Origin: " . implode(', ', $config['origins']));
        header("Access-Control-Allow-Methods: " . implode(', ', $config['allowed_methods']));
        header("Access-Control-Allow-Headers: " . implode(', ', $config['allowed_headers']));
        header("Access-Control-Allow-Credentials: true");

        if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
            http_response_code(204);
            exit();
        }
    }

    function run()
    {
        require parseDir(__DIR__) . '/../Routes/routes.php';
    }

    static function getDatabase(): Database
    {
        return static::$database;
    }
}
