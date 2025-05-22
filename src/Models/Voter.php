<?php

namespace Src\Models;

class Voter
{
    public string $voterId, $name, $precinct, $address;
    public bool $isGiven;
}
