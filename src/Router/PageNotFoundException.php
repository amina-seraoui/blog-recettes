<?php

namespace App\Router;

class PageNotFoundException extends \Exception
{
    public function __construct(string $message = 'Page not found', int $code = 404, \Throwable $previous = null)
    {
        parent::__construct($message, $code, $previous);
    }
}
