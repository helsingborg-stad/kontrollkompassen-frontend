<?php

declare(strict_types=1);

namespace KoKoP;

use \ComponentLibrary\Init as ComponentLibraryInit;
use \KoKoP\Interfaces\AbstractApp;
use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\View;

function getAction(): string | bool
{
    return isset($_GET['action']) ? $_GET['action'] : false;
}

function getCurrentPath(string $default): string
{
    $url = rtrim(preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']), '/');
    return $url !== '' ? $url : $default;
}

class App implements AbstractApp
{
    private AbstractServices $services;

    public function __construct(AbstractServices $services)
    {
        define('VIEWS_PATH', BASEPATH . 'views/');
        define('BLADE_CACHE_PATH', '/tmp/cache/');
        define('LOCAL_DOMAIN', '.local');

        $this->services = $services;
    }

    public function loadPage(): void
    {
        $data['pageNow'] = getCurrentPath('home');
        $data['action'] = getAction();

        $view = new View($this->services);

        $view->show($data['pageNow'], $data, (new ComponentLibraryInit([]))->getEngine());
    }
}
