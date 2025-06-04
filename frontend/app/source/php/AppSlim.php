<?php

declare(strict_types=1);

namespace KoKoP;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use \ComponentLibrary\Init as ComponentLibraryInit;

use \KoKoP\Interfaces\AbstractApp;
use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Interfaces\AbstractView;
use \KoKoP\ViewSlim;

use Slim\App as SlimApp;

function getViewContent(AbstractView $view, string $pageNow, mixed $data): string
{
    ob_start();
    $view->show($pageNow, $data, new ComponentLibraryInit([])->getEngine());
    $viewContent = ob_get_contents();
    ob_end_clean();

    return $viewContent;
}

class AppSlim implements AbstractApp
{
    private SlimApp $slimApp;
    private AbstractView $view;

    public function __construct(AbstractServices $services)
    {
        define('VIEWS_PATH', BASEPATH . 'views/');
        define('BLADE_CACHE_PATH', '/tmp/cache/');
        define('LOCAL_DOMAIN', '.local');

        $this->slimApp = AppFactory::create();
        $this->view = new ViewSlim($services);
    }

    public function loadPage(): void
    {
        $this->slimApp->map(['GET', 'POST'], '/', function (Request $request, Response $response) {
            $data['action'] = $request->getQueryParams()['action'] ?? '';

            $response->getBody()->write(getViewContent($this->view, 'home', $data));
            return $response;
        });

        $this->slimApp->map(['GET', 'POST'], '/uppslag', function (Request $request, Response $response) {
            $data['action'] = $request->getQueryParams()['action'] ?? '';

            $response->getBody()->write(getViewContent($this->view, 'uppslag', $data));
            return $response;
        });

        $this->slimApp->get('/glomt-losenord', function (Request $request, Response $response) {
            $data['action'] = $request->getQueryParams()['action'] ?? '';

            $response->getBody()->write(getViewContent($this->view, 'glomt-losenord', $data));
            return $response;
        });

        $this->slimApp->run();
    }
}
