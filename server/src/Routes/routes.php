<?php

use Src\Controllers\VoterController;
use Src\Core\Router;

$router = new Router(base: '/api/v1');

$router->get("/voters", [VoterController::class, "getVoters"]);

$router->post("/voters/import", [VoterController::class, "import"]);

$router->post('/voters/export', [VoterController::class, 'export']);

$router->patch("/voters/{voterId}", [VoterController::class, "changeStatus"]);

$router->delete("/vouters", [VoterController::class, "clearVoters"]);

$router->_404(function () {
    status(404);
    return json(["message" => "Requested resource not found."]);
});
