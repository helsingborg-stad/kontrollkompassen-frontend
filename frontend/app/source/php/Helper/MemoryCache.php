<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Interfaces\AbstractCache;
use \KoKoP\Interfaces\AbstractSecure;
use \KoKoP\Interfaces\AbstractResponse;

class MemoryCache implements AbstractCache
{
    private $cache = null;
    private ?AbstractSecure $secure = null;

    public function __construct(?AbstractSecure $secure = null)
    {
        $this->secure = $secure;
        $this->cache = array();
    }
    public function set(AbstractResponse $response, int $ttl = 300): void
    {
        if ($key = $response->getHash()) {
            // Get content
            $content = $response->getContent();
            if ($this->secure) {
                // Encrypt
                $content = $this->secure->encrypt($content);
            } else {
                // Convert objects to string
                if (!is_string($content)) {
                    $content = json_encode($content);
                }
            }
            // Push to array
            $this->cache += [$key => $content];
        }
    }

    public function get(string $key): mixed
    {
        if (isset($this->cache[$key])) {
            $content = $this->cache[$key];
            // Decrypt
            if ($this->secure) {
                return $this->secure->decrypt($content);
            }
            // Return (decrypted) data
            return json_decode($content);
        }
        return null;
    }
}
