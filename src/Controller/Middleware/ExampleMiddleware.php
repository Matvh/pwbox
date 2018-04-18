<?php

namespace SlimApp\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

class ExampleMiddleware{

    public function __invoke(Request $request, Response $response, callable $next)
    {
        //$response->getBody()->write('BEFORE');
        $next($request,$response);
        //$response->getBody()->write('AFTER');
        return $response;
    }
}