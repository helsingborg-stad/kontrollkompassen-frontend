<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

use \KoKoP\Interfaces\AbstractView;

interface AbstractApp
{
    public function loadPage(): void;
}
