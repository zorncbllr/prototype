<?php

namespace Src\Repositories;

use PDO;
use Src\Core\Database;
use Src\Models\Voter;

class VoterRepository
{

    public function __construct(
        protected Database $database
    ) {}

    /** @return array<Voter> */
    public function getAllVoters(): array
    {
        $stmt = $this->database->prepare(
            "SELECT * FROM voters"
        );

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_CLASS, Voter::class);
    }

    /** @param array<Voter> $imports  */
    public function createVoter(array $imports)
    {
        $query = "INSERT INTO voters (name, precinct) VALUES ";

        $values = [];
        $params = [];

        foreach ($imports as $index => $voter) {
            $values[] = "(:name_$index, :precinct_$index)";
            $params["name_$index"] = $voter->name;
            $params["precinct_$index"] = $voter->precinct;
        }

        $query .= implode(", ", $values);

        $stmt = $this->database->prepare($query);

        $stmt->execute([...$params]);
    }
}
