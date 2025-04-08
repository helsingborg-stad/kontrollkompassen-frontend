<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractRequest
{
    public function get(string $url, $queryParams = [], $headers = []): AbstractResponse;
    public function post(string $url, $data = [], $headers = []): AbstractResponse;
}
