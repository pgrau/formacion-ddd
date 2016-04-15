<?php

namespace Training\Infrastructure\UI\Controller;

use FastRoute\RouteCollector;
use Training\Application\Service\User\Access\AuthenticateUserRequest;

final class Login extends Base
{
    public static function addRoutes(RouteCollector $routeCollector)
    {
        $routeCollector->addRoute('GET', '/login', [self::class, 'init']);
        $routeCollector->addRoute('POST', '/login', [self::class, 'save']);
    }

    public function init()
    {
        /** @var $tpl \League\Plates\Engine */
        $tpl = $this->container->get('template');

        return $tpl->render('login::index', []);
    }

    public function save()
    {
        try {
            $request = new AuthenticateUserRequest($_POST['inputEmail'], $_POST['inputPassword']);
            $useCase = $this->container->get('authenticate_user');
            $response = $useCase->execute($request);

            /** @var $tpl \League\Plates\Engine */
            $tpl = $this->container->get('template');

            return $tpl->render('login::result', ['user' => $response->user()]);
        }
        catch (\Exception $e) {
            echo $e->getMessage();
            die;
        }
    }
}