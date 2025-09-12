<?php

declare(strict_types=1);

use function DI\factory;

use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Services\ServicesFactory;

return [
    AbstractServices::class => factory([ServicesFactory::class, 'create']),
];
