<?php

declare(strict_types=1);

namespace Src\Core;

class Kernel
{
    function __construct(private int $argc, private array $argv) {}

    function run()
    {
        switch ($this->argv[1]) {
            case 'serve':
                $port = $_ENV['SERVER_PORT'] ?? 3000;
                shell_exec("php -S localhost:$port -t public -f index.php");
                break;

            default:
            case "clear":
                try {
                    $logs = parseDir(__DIR__) . "/../Logs/logs.txt";
                    unlink($logs);
                } catch (\Throwable $e) {
                }
                break;
            case "-g":
                $this->handleGenerate();
                break;
            case "enable":
                if ($this->argv[2] == "images") {
                    shell_exec("sudo chown -R \$USER:82 ./public/images && sudo chmod -R 775 ./public/images");
                }
                if ($this->argv[2] == "logs") {
                    shell_exec("sudo chown -R \$USER:82 ./src/Logs && sudo chmod -R 775 ./src/Logs");
                }
                break;
        }
    }

    function handleGenerate()
    {
        try {
            $name = $this->argv[3];

            switch ($this->argv[2]) {
                case 'resource':
                    $this->generateController($name);
                    $this->generateService($name);
                    $this->generateRepository($name);
                    $this->generateModel($name);
                    break;
                case 'repository':
                    $this->generateRepository($name);
                    break;
                case 'model':
                    $this->generateModel($name);
                    break;
            }
        } catch (\Throwable $e) {
        }
    }

    function generateController(string $name)
    {
        $capital = ucfirst($name);
        $camel = lcfirst($name);

        $path = parseDir(__DIR__) . "/../Controllers/{$capital}Controller.php";

        file_put_contents($path, "<?php

namespace Src\Controllers;

use Src\Core\App;
use Src\Services\\{$capital}Service;
use Src\Repositories\\{$capital}Repository;

class {$capital}Controller
{
    protected {$capital}Service \${$camel}Service;

    public function __construct() 
    {
        \$database = App::getDatabase();

        \$this->{$camel}Service = new {$capital}Service(
            database: \$database,
            {$camel}Repository: new {$capital}Repository(\$database)
        );
    }

    public function index() {}

}");
    }

    function generateService(string $name)
    {
        $capital = ucfirst($name);
        $camel = lcfirst($name);

        $path = parseDir(__DIR__) . "/../Services/{$capital}Service.php";

        file_put_contents($path, "<?php

namespace Src\Services;

use Src\Core\Database;
use Src\Repositories\\{$capital}Repository;

class {$capital}Service
{

    public function __construct(
        protected Database \$database,
        protected {$capital}Repository \${$camel}Repository
    ) {}

    public function index() {}

}");
    }

    function generateRepository(string $name)
    {
        $capital = ucfirst($name);

        $path = parseDir(__DIR__) . "/../Repositories/{$capital}Repository.php";

        file_put_contents($path, "<?php

namespace Src\Repositories;

use PDO;
use Src\Core\Database;

class {$capital}Repository
{

    public function __construct(
        protected Database \$database
    ) {}

    public function getAll{$capital}() 
    {
        \$stmt = \$this->database->prepare(\"\");

        \$stmt->execute();

        return \$stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}");
    }

    function generateModel(string $name)
    {
        $capital = ucfirst($name);
        $camel = lcfirst($name);

        $path = parseDir(__DIR__) . "/../Models/{$capital}.php";

        file_put_contents($path, "<?php

namespace Src\Models;

class {$capital}
{
    public int \${$camel}Id;
}");
    }
}
