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

    public function createVoter(string $name, string $address, string $precinct)
    {
        $stmt = $this->database->prepare(
            "INSERT INTO voters (name, address, precinct)
            VALUES (:name, :address, :precinct)"
        );

        $stmt->execute([
            "name" => $name,
            "address" => $address,
            "precinct" => $precinct
        ]);
    }
}
