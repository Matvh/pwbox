<?php
/**
 * Created by PhpStorm.
 * User: MatiasJVH
 * Date: 12/04/2018
 * Time: 19:25
 */

namespace SlimApp\Controller;


use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Container\ContainerInterface;

class PostUserController
{
    protected $container;

    /**
     * HelloController constructor.
     * @param $container
     */
    public function __construct(ContainerInterface $container) {
        $this->container = $container;
    }

    public function __invoke(Request $request, Response $response){
        try{
            $data = $request->getParsedBody();

            //TODO VALIDATE --> ver que sea un mail valido (bien escrito)
            //isset($data['email']);
            //Si todo esta bien ejecutamos las 2 de abajo
            $service = $this->container->get('post_user_use_case');
            $service($data);
        }catch (\Exception $e){
            $response = $response
                ->withStatus(500)
                ->withHeader('Content-type', 'txt/html')
                ->write($e->getMessage());
        }
        return $response;
    }

}