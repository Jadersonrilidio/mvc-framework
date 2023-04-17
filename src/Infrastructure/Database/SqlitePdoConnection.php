<?php

declare(strict_types=1);

namespace Jayrods\MvcFramework\Infrastructure\Database;

use Jayrods\MvcFramework\Infrastructure\Database\PdoConnection;
use PDO;

class SqlitePdoConnection extends PdoConnection
{
    /**
     * 
     */
    protected function connect(): void
    {
        $dsn = 'sqlite:' . DATABASE_PATH . env('DB_PATH', 'database.sqlite');

        self::$connection = new PDO($dsn);
    }
}
