<?php

declare(strict_types=1);

namespace KoKoP;

use HelsingborgStad\BladeService\BladeServiceInterface;

use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Interfaces\AbstractView;

class ViewSlim implements AbstractView
{
    protected AbstractServices $services;

    public function __construct(AbstractServices $services)
    {
        $this->services = $services;
    }

    public function loadControllerData(string $view): array
    {
        $view = ucfirst(trim(str_replace(' ', '', ucwords(str_replace(array('-', '/'), ' ', $view))), '/'));

        if (!file_exists(__DIR__ . '/Controller/' . $view . '.php')) {
            return [];
        }

        $controller = 'KoKoP\\Controller\\' . $view;

        return new $controller($this->services)->data;
    }

    public function show(string $view, array $data, BladeServiceInterface $blade): void
    {
        $bladeResult = preg_replace('/(id|href)=""/', '', $blade->makeView(
            'pages.' . $view,
            $data,
            $this->loadControllerData($view)
        )->render());

        echo $bladeResult;
    }
}
