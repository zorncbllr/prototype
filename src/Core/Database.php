<?php

declare(strict_types=1);

namespace Src\Core;

use PDO;
use PDOException;

class Database extends PDO
{

    function __construct()
    {
        $config = require parseDir(__DIR__) . '/../Config/database.conf.php';

        $dsn = "mysql:" . http_build_query($config, "", ";");

        try {
            parent::__construct(
                $dsn,
                $config['user'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
                ]
            );
        } catch (PDOException $e) {

            echo $e->getMessage();

            status(500);
            die("Database Exception: Database server might be down or might be due to incorrect configurations.");
        }
    }
}
