<?php

namespace Src\Core;

class Route
{
    function __construct(
        protected string $route,
        protected Router $router
    ) {}

    /** @return Route */
    function middleware(string ...$middleware): Route
    {
        $this->router = new Router(
            base: $this->router->base,
            middlewares: [...$this->router->middlewares, ...$middleware]
        );

        return $this;
    }

    /** @return Route */
    function get(callable|array $dispatcher): Route
    {
        $this->router->get($this->route, $dispatcher);
        return $this;
    }

    /** @return Route */
    function post(callable|array $dispatcher): Route
    {
        $this->router->post($this->route, $dispatcher);
        return $this;
    }

    /** @return Route */
    function patch(callable|array $dispatcher): Route
    {
        $this->router->patch($this->route, $dispatcher);
        return $this;
    }

    /** @return Route */
    function put(callable|array $dispatcher): Route
    {
        $this->router->put($this->route, $dispatcher);
        return $this;
    }

    /** @return Route */
    function delete(callable|array $dispatcher): Route
    {
        $this->router->delete($this->route, $dispatcher);
        return $this;
    }
}
