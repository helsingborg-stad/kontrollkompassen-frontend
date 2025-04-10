<?php

namespace KoKoP;

use ComponentLibrary\Init as ComponentLibraryInit;
use \KoKoP\Services\RuntimeServices;

class App
{
    protected $default = 'home';
    private RuntimeServices $services;

    public function __construct(array $config = array())
    {
        $this->setUpEnvironment();

        $this->services = new RuntimeServices($config);

        $this->loadPage(
            $this->getCurrentPath(),
            $this->getAction()
        );
    }

    private function setUpEnvironment()
    {
        define('VIEWS_PATH', BASEPATH . 'views/');
        define('BLADE_CACHE_PATH', '/tmp/cache/');
        define('LOCAL_DOMAIN', '.local');
    }

    private function getCurrentPath()
    {
        $url = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
        $url = rtrim($url, '/');
        return ($url !== "") ? $url : $this->default;
    }

    private function getAction()
    {
        return isset($_GET['action']) ? $_GET['action'] : false;
    }

    public function loadPage($page, $action)
    {
        $blade = (new ComponentLibraryInit([]))->getEngine();

        $data['pageNow'] = $page;
        $data['action'] = $action;

        $view = new \KoKoP\View($this->services);

        return $view->show(
            $page,
            $data,
            $blade
        );
    }
}
