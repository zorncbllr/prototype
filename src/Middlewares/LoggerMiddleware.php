<?php

namespace Src\Middlewares;

use Src\Core\Middleware;
use Src\Core\Request;

class LoggerMiddleware extends Middleware
{

    public function runnable(Request $request, callable $next)
    {
        $logsPath = parseDir(__DIR__) . "/../Logs";

        if (!is_dir($logsPath)) mkdir($logsPath);

        $logFile = $logsPath . "/logs.txt";

        if (!file_exists($logFile)) {
            file_put_contents($logFile, "");
        }

        $content = file_get_contents($logFile);
        $cokies = implode(", ", $_COOKIE);

        $content .= "[ REQUEST: $request->method -> $request->uri, COOKIES:  $cokies ]\n";

        file_put_contents($logFile, $content);

        return $next();
    }
}
