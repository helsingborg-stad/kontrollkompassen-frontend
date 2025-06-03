<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

use HelsingborgStad\BladeService\BladeServiceInterface;

interface AbstractView
{
    public function loadControllerData(string $view): array;

    public function show(string $view, array $data, BladeServiceInterface $blade): void;
}
