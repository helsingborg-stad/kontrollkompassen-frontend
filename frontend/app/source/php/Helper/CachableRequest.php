<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Interfaces\AbstractCache;
use \KoKoP\Interfaces\AbstractResponse;

class CachableRequest implements AbstractRequest
{
    protected AbstractCache $cache;
    protected AbstractRequest $request;

    public function __construct(AbstractCache $cache, AbstractRequest $request)
    {
        $this->cache = $cache;
        $this->request = $request;
    }

    public function get(string $url, $queryParams = [], $headers = []): AbstractResponse
    {
        $queryString = '';
        if (!empty($queryParams)) {
            $queryString = '?' . http_build_query($queryParams);
        }
        // Format cache-key
        $key = "data:" . md5('GET' . $url . $queryString);

        // Fetch from cache
        if ($cached = $this->cache->get($key)) {
            return new Response(200, $key, $cached);
        }
        // Execute request
        $response = $this->request->get($url, $queryParams, $headers);

        $cachable = new Response($response->getStatusCode(), $key, $response->getContent());
        // Save to cache
        if (!$cachable->isErrorResponse()) {
            $this->cache->set($cachable);
        }
        return $cachable;
    }

    public function post(string $url, $data = [], $headers = []): AbstractResponse
    {
        // Format cache-key
        $key = "data:" . md5('POST' . $url . json_encode($data));

        // Fetch from cache
        if ($cached = $this->cache->get($key)) {
            return new Response(200, $key, $cached);
        }
        // Execute request
        $response = $this->request->post($url, $data, $headers);

        $cachable = new Response($response->getStatusCode(), $key, $response->getContent());
        // Save to cache
        if (!$cachable->isErrorResponse()) {
            $this->cache->set($cachable);
        }
        return $cachable;
    }
}
