<?php

namespace KoKoP;

use ComponentLibrary\Init as ComponentLibraryInit;
use \KoKoP\Services\RuntimeServices;

/**
 * Class App
 * @package KoKoP
 */
class App
{
    protected $default = 'home'; //Home
    private RuntimeServices $services;
    /**
     * App constructor.
     * @param $blade
     */
    public function __construct(array $config = array())
    {
        //Setup constants
        $this->setUpEnvironment();

        // Create services
        $this->services = new RuntimeServices($config);

        //Load current page
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

    /** 
     * Find out the current page 
     */
    private function getCurrentPath()
    {
        $url = preg_replace('/\?.*/', '', $_SERVER['REQUEST_URI']);
        $url = rtrim($url, '/');
        return ($url !== "") ? $url : $this->default;
    }

    /**
     * Get the requested action data
     */
    private function getAction()
    {
        return isset($_GET['action']) ? $_GET['action'] : false;
    }

    /**
     * Loads a page and it's navigation
     * @return bool Returns true when the page is loaded
     */
    public function loadPage($page, $action)
    {
        $blade = (new ComponentLibraryInit([]))->getEngine();

        //Current page 
        $data['pageNow']                        = $page;
        $data['action']                         = $action;

        //Render page 
        $view = new \KoKoP\View($this->services);

        return $view->show(
            $page,
            $data,
            $blade
        );
    }
}
