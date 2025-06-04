<?php

declare(strict_types=1);

namespace KoKoP;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Factory\AppFactory;

use \ComponentLibrary\Init as ComponentLibraryInit;

use \KoKoP\Interfaces\AbstractView;

function getViewContent(AbstractView $view, string $pageNow, mixed $data): string
{
    ob_start();
    $view->show($pageNow, $data, new ComponentLibraryInit([])->getEngine());
    $viewContent = ob_get_contents();
    ob_end_clean();

    return $viewContent;
}

class AppSlim extends AbsAppClass
{
    public function loadPage(): void
    {
        $slimApp = AppFactory::create();

        $slimApp->map(['GET', 'POST'], '/', function (Request $request, Response $response) {
            $data['action'] = $request->getQueryParams()['action'] ?? '';

            $response->getBody()->write(getViewContent($this->view, 'home', $data));
            return $response;
        });

        $slimApp->map(['GET', 'POST'], '/uppslag', function (Request $request, Response $response) {
            $data['action'] = $request->getQueryParams()['action'] ?? '';

            $response->getBody()->write(getViewContent($this->view, 'uppslag', $data));
            return $response;
        });

        $slimApp->get('/glomt-losenord', function (Request $request, Response $response) {
            $data['action'] = $request->getQueryParams()['action'] ?? '';

            $response->getBody()->write(getViewContent($this->view, 'glomt-losenord', $data));
            return $response;
        });

        $slimApp->run();
    }
}
