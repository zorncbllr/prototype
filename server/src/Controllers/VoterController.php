<?php

namespace Src\Controllers;

use Src\Core\App;
use Src\Core\Request;
use Src\Services\VoterService;
use Src\Repositories\VoterRepository;

class VoterController
{
    protected VoterService $voterService;

    public function __construct()
    {
        $database = App::getDatabase();

        $this->voterService = new VoterService(
            database: $database,
            voterRepository: new VoterRepository($database)
        );
    }

    public function getVoters()
    {
        status(200);
        return json($this->voterService->getAllVoters());
    }

    public function import(Request $request)
    {
        $inputFile = $request->files->pdf ?? null;

        if (!$inputFile) {
            status(400);
            return json(["message" => "PDF file is required."]);
        }

        $this->voterService->import($inputFile);

        status(200);
        return json(["message" => "File data has been imported."]);
    }

    public function changeStatus(Request $request)
    {
        $voterId = $request->params->voterId;
        $value = $request->body->value;

        $this->voterService->changeStatus($voterId, $value);

        status(200);
        return json(["message" => "Voter status has been updated"]);
    }

    public function export()
    {
        $file = $this->voterService->export();

        status(200);
        return json([
            "message" => "File has been exported.",
            "file" => $file
        ]);
    }

    public function clearVoters()
    {
        $this->voterService->clearVoters();

        status(204);
        return json(["message" => "All data has been cleared."]);
    }
}
