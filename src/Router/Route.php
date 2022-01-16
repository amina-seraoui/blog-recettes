<?php

namespace App\Router;

use Attribute;

#[Attribute(Attribute::TARGET_METHOD|Attribute::TARGET_CLASS)]
class Route
{
    private array $patterns = [
        'i' => '[0-9]+',
        'slug' => '[\p{L}\-0-9]+'
    ];

    private array $matches = [];

    /**
     * @param callable $callable
     */
    public function __construct(string $path, private $callable)
    {
        $this->path = trim($path, '/');
    }

    public function run ()
    {
        return call_user_func_array($this->callable, $this->matches);
    }

    public function match(string $url): bool
    {
        $url = trim($url, '/');
        $path = preg_replace_callback('#\[(\w+)(?::(\w+))?\]#', [$this, 'paramMatch'], $this->path,);

        $regex = '#^' . $path . '(?:\?.*)?$#';
        if (!preg_match($regex, $url, $matches)) {
            return false;
        }

        array_shift($matches);
        $this->matches = $matches;
        return true;
    }

    public function getURL (array $params): string
    {
        $path = $this->path;
        foreach ($params as $k => $v) {
            $path = preg_replace('#\[' . $k . '(:(\w+))?\]#', $v, $path);
        }

        return $path;
    }

    private function paramMatch($match)
    {
        if (isset($this->patterns[$match[2]])) {
            return '(' . $this->patterns[$match[2]] . ')';
        }

        return '([^/]+)';
        dd($match);
    }

    private function addPattern (string $key, string $pattern)
    {
        $this->patterns[$key] = str_replace('(', '(?:', $pattern);
        return $this;
    }
}