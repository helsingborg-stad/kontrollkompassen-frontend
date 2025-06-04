<?php

declare(strict_types=1);

namespace KoKoP;

use \ComponentLibrary\Init as ComponentLibraryInit;

use \KoKoP\Interfaces\AbstractApp;
use \KoKoP\Interfaces\AbstractView;


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
    protected AbstractView $view;

    public function __construct(AbstractView $view)
    {
        $this->view = $view;
    }

    public function loadPage(): void
    {
        $data['pageNow'] = getCurrentPath('home');
        $data['action'] = getAction();

        $this->view->show($data['pageNow'], $data, (new ComponentLibraryInit([]))->getEngine());
    }
}
