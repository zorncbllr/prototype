<?php

namespace Src\Services;

use PHPUnit\Util\PHP\JobRunnerRegistry;
use Src\Core\Database;
use Src\Models\Voter;
use Src\Repositories\VoterRepository;

class VoterService
{

    public function __construct(
        protected Database $database,
        protected VoterRepository $voterRepository
    ) {}

    /** @return array<Voter> */
    public function getAllVoters(): array
    {
        return $this->voterRepository->getAllVoters();
    }

    public function import($importedFile)
    {
        $text = shell_exec("pdftotext " . $importedFile->tmp_name . " -");

        $matches = null;

        if (preg_match_all(
            "/\n{1,2}(?:[*A-D]{1,3}\h+)?([\p{L}._-]+(?:\h+[\p{L}._-]+)*,\h*[\p{L}\h.'_-]+(?:,?\h*(?:[JS]R\.?|J\h*R\.?|II\.?|III\.?|IV\.?|VI{0,3}|IX|X|V)\h*)?[\p{L}\h.'_-]*)(?=\n\n)/u",
            $text,
            $matches
        )) {

            $names = [];

            // return json(sizeof($matches[0]));

            // return json($matches);

            foreach ($matches[0] as $match) {
                if (preg_match("/\n{0,2}([^\n]*?,[^\n]+)/u", $match, $name)) {
                    $names[] = $name[1];
                }
            }

            return json([
                "length" => sizeof($names),
                "voters" => $names
            ]);
        }
    }
}
