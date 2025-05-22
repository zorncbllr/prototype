<?php

function json(mixed $data)
{
    header('Content-Type: application/json');

    echo json_encode($data);
    exit;
}

function status(int $code)
{
    http_response_code($code);
}

function view(string $view, array $variables = [])
{
    extract($variables);
    require parseDir(__DIR__) . "/../../Views/$view.php";
}

function redirect(string $uri = "")
{
    if (!empty($uri)) {
        header("Location: $uri");
        return exit;
    }

    return new class {
        private function moveTo(string $uri, string $method)
        {
            $_SERVER['REQUEST_METHOD'] = $method;
            $_SERVER['PATH_INFO'] = $uri;
            require parseDir(__DIR__) . '/../../routes/routes.php';
        }

        function get(string $uri)
        {
            $this->moveTo($uri, 'GET');
        }

        function post(string $uri)
        {
            $this->moveTo($uri, 'POST');
        }

        function patch(string $uri)
        {
            $this->moveTo($uri, 'PATCH');
        }

        function put(string $uri)
        {
            $this->moveTo($uri, 'PUT');
        }

        function delete(string $uri)
        {
            $this->moveTo($uri, 'DELETE');
        }
    };
}
