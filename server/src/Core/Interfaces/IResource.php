<?php

namespace Src\Core\Interfaces;

use Src\Core\Request;

interface IResource
{
    public function getAll(Request $request);
    public function getById(Request $request, string $id);
    public function create(Request $request);
    public function update(Request $request, string $id);
    public function delete(Request $request, string $id);
}
