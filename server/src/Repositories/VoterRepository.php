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

    public function getVoterById(string $voterId): Voter|false
    {
        $stmt = $this->database->prepare(
            "SELECT * FROM voters WHERE voterId = :voterId"
        );

        $stmt->execute(["voterId" => $voterId]);

        return $stmt->fetchObject(Voter::class);
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

    public function changeStatus(string $voterId, bool $value)
    {
        $value = $value ? 1 : 0;

        $stmt = $this->database->prepare(
            "UPDATE voters SET isGiven = $value WHERE voterId = :voterId"
        );

        $stmt->execute(["voterId" => $voterId]);
    }
}
