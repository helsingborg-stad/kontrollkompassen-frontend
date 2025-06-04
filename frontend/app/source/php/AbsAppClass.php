<?php

declare(strict_types=1);

namespace KoKoP;

use \KoKoP\Interfaces\AbstractApp;
use \KoKoP\Interfaces\AbstractView;

abstract class AbsAppClass implements AbstractApp
{
    protected AbstractView $view;

    public function __construct(AbstractView $view)
    {
        define('VIEWS_PATH', BASEPATH . 'views/');
        define('BLADE_CACHE_PATH', '/tmp/cache/');
        define('LOCAL_DOMAIN', '.local');

        $this->view = $view;
    }
}
