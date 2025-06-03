<?php

declare(strict_types=1);

namespace KoKoP;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use \ComponentLibrary\Init as ComponentLibraryInit;

use \KoKoP\Interfaces\AbstractApp;
use \KoKoP\Interfaces\AbstractServices;
use Slim\App;

class AppSlim implements AbstractApp
{
    private AbstractServices $services;
    private App $slimApp;

    public function __construct(AbstractServices $services)
    {
        define('VIEWS_PATH', BASEPATH . 'views/');
        define('BLADE_CACHE_PATH', '/tmp/cache/');
        define('LOCAL_DOMAIN', '.local');

        $this->services = $services;
        $this->slimApp = AppFactory::create();
    }

    public function loadPage(): void
    {
        $view = new \KoKoP\ViewSlim($this->services);

        $this->slimApp->get('/', function (Request $request, Response $response) use ($view) {

            $action = $request->getQueryParams()['action'] ?? '';

            ob_start();
            $view->show('home', ['pageNow' => 'home', 'action' => $action], new ComponentLibraryInit([])->getEngine());
            $viewContent = ob_get_contents();
            ob_end_clean();

            $response->getBody()->write($viewContent);
            return $response->withHeader('Content-Type', 'text/html', 'charset=UTF-8')
                ->withStatus(200);
        });

        $this->slimApp->post('/', function (Request $request, Response $response) use ($view) {

            $action = $request->getQueryParams()['action'] ?? '';

            ob_start();
            $view->show('home', ['pageNow' => 'home', 'action' => $action], new ComponentLibraryInit([])->getEngine());
            $viewContent = ob_get_contents();
            ob_end_clean();

            $response->getBody()->write($viewContent);
            return $response->withHeader('Content-Type', 'text/html', 'charset=UTF-8')
                ->withStatus(200);
        });

        $this->slimApp->run();
    }
}
