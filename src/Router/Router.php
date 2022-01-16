<?php

namespace App\Router;

class Router
{
    private array $routes = [];
    private array $namedRoutes = [];

    public function __construct(private string $url) {}

    public function get(string $path, string|callable $callable, ?string $name = null): self
    {
        return $this->add($path, $callable, $name);
    }

    public function post(string $path, string|callable $callable, ?string $name = null): self
    {
        return $this->add($path, $callable, $name, 'POST');
    }

    private function add(string $path, string|callable $callable, ?string $name = null, string $method = 'GET'): self
    {
        if (is_string($callable)) {
            $callable = new $callable();
        }

        $route = $this->routes[$method][] = new Route($path, $callable, $name);

        if(!is_null($name)) {
                $this->namedRoutes[$name] = $route;
        }

        return $this;
    }

    public function match()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        if (!isset($this->routes[$method])) {
            throw new PageNotFoundException('No routes on this method');
        }
        
        foreach ($this->routes[$method] as $route) {
            if ($route->match($this->url)) {
                return $route->run();
            }
        }
        throw new PageNotFoundException('No routes match');
    }

    public function getURL(string $name, array $params = []): string
    {
        if (isset($this->namedRoutes[$name])) {
            return $this->namedRoutes[$name]->getURL($params);
        }

        throw new PageNotFoundException('No route matches this name');
    }
}
