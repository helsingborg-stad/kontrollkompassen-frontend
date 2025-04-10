<?php

namespace KoKoP;

use ComponentLibrary\Init as ComponentLibraryInit;
use \KoKoP\Services\RuntimeServices;
use \KoKoP\Interfaces\AbstractServices;

class App
{
    protected $default = 'home';
    private AbstractServices $services;

    public function __construct(array $config)
    {
        $this->setUpEnvironment();
        $this->services = new RuntimeServices($config);
    }

    private function setUpEnvironment()
    {
        define('VIEWS_PATH', BASEPATH . 'views/');
        define('BLADE_CACHE_PATH', '/tmp/cache/');
        define('LOCAL_DOMAIN', '.local');
    }

    private function getCurrentPath(): string
    {
        $url = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
        $url = rtrim($url, '/');
        return ($url !== "") ? $url : $this->default;
    }

    private function getAction(): string | bool
    {
        return isset($_GET['action']) ? $_GET['action'] : false;
    }

    public function loadPage()
    {
        $data['pageNow'] = $this->getCurrentPath();
        $data['action'] = $this->getAction();

        $view = new \KoKoP\View($this->services);

        $view->show($data['pageNow'], $data, (new ComponentLibraryInit([]))->getEngine());
    }
}
