<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 19/04/2018
 * Time: 18:45
 */

namespace SlimApp\Controller\Middleware;

use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;

/**
 * Class SessionMiddleware
 * @package SlimApp\Controller\Middleware
 */
class SessionMiddleware{

    public function __invoke(Request $request, Response $response, callable $next){
        session_start();
        return $next($request,$response);
    }


}