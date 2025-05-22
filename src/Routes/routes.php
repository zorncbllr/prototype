<?php

use Src\Controllers\VoterController;
use Src\Core\Router;

$router = new Router();

$router->get("/", [VoterController::class, "index"]);
$router->post("/import", [VoterController::class, "import"]);

$router->_404(function () {
    status(404);
    return json(["message" => "Requested resource not found."]);
});
