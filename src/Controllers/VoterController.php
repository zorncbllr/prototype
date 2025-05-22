<?php

namespace Src\Controllers;

use Src\Core\App;
use Src\Core\Exceptions\ServiceException;
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

    public function index()
    {
        $voters = $this->voterService->getAllVoters();

        return view("Voters", ["voters" => $voters]);
    }

    public function import(Request $request)
    {
        $inputFile = $request->files->pdf;

        $text = $this->voterService->import($inputFile);

        return json($text);
    }
}
