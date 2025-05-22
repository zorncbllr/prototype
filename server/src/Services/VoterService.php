<?php

namespace Src\Services;

use PDOException;
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

        if (preg_match_all("/Prec : \w+/", $text, $precincts)) {
            $precinctMap = [];

            foreach ($precincts[0] as $precinct) {
                $precinctMap[$precinct] = $precinct;
            }

            $precincts = array_map(
                fn($prec) => str_replace("Prec : ", "", $prec),
                array_keys($precinctMap)
            );
        }

        if (preg_match_all(
            "/\n{1,2}(?:[*A-D]{1,3}\h+)?([\p{L}._-]+(?:\h+[\p{L}._-]+)*,\h*[\p{L}\h.'_-]+(?:,?\h*(?:[JS]R\.?|J\h*R\.?|II\.?|III\.?|IV\.?|VI{0,3}|IX|X|V)\h*)?[\p{L}\h.'_-]*)(?=\n\n)/u",
            $text,
            $matches
        )) {

            $index = 0;
            $precinctChanged = false;
            $voters = [];

            $legendCombinations = [
                '*',
                'A',
                'B',
                'C',
                'D',
                '*A',
                '*B',
                '*D',
                'AB',
                'AC',
                'AD',
                'BC',
                'BD',
                'CD',
                '*AB',
                '*AD',
                '*BD',
                'ABC',
                'ABD',
                'ACD',
                'BCD',
                'ABCD',
                '*ABD'
            ];

            foreach ($matches[0] as $match) {

                if (!preg_match("/([^\n]*?,[^\n]+)/u", $match, $name)) {
                    continue;
                }

                $name = $name[0];

                $parts = explode(" ", $name);

                if (in_array($parts[0], $legendCombinations)) {
                    array_shift($parts);
                    $name = implode(" ", $parts);
                }

                if ($name[0] != 'A') {
                    $precinctChanged = true;
                }

                if ($name[0] == 'A' && $precinctChanged) {
                    $precinctChanged = false;
                    $index++;
                }

                $voter = new Voter();
                $voter->name = $name;
                $voter->precinct = $precincts[$index];

                $voters[] = $voter;
            }

            try {
                $this->voterRepository->createVoter($voters);
            } catch (PDOException $e) {
            }
        }
    }

    public function changeStatus(string $voterId, bool $value)
    {
        try {

            $this->voterRepository->changeStatus($voterId, $value);
        } catch (PDOException $e) {
        }
    }
}
