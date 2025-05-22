<?php

namespace Src\Core;

use AllowDynamicProperties;

#[AllowDynamicProperties]
class Request
{
    public string $method, $uri, $baseUrl;
    public object $query, $body, $params, $files, $cookies, $headers;

    function __construct()
    {
        $this->body = (object) json_decode(file_get_contents("php://input"), associative: false);
        $this->query = (object) json_decode(json_encode($_GET), associative: false);
        $this->files = (object) json_decode(json_encode($_FILES), associative: false);
        $this->cookies = (object) json_decode(json_encode($_COOKIE), associative: false);
        $this->headers = (object) json_decode(json_encode(getallheaders() ?? []), associative: false);

        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->baseUrl = 'http://' . $_SERVER['HTTP_HOST'];

        foreach ($_REQUEST as $key => $value) {
            $this->$key =  is_array($value) ? $value : htmlspecialchars($value);
        }
    }

    function setParams(array $params)
    {
        $this->params = (object) json_decode(json_encode($params), associative: false);
    }
}
