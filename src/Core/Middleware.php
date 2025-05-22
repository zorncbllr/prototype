<?php

namespace Src\Core;

abstract class Middleware
{
    abstract function runnable(Request $request, callable $next);
}
