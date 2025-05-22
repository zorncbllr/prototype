<?php

function parseDir(string $directory): string
{
    return str_replace("\\", "/", $directory);
}

function debugPrint(mixed $variable)
{
    echo "<pre>";
    var_dump($variable);
    echo "</pre>";
}
