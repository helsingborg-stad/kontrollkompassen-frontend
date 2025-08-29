<?php

declare(strict_types=1);

namespace KoKoP\Services;

use \KoKoP\Helper\RedisCache;
use \KoKoP\Helper\Secure;
use \KoKoP\Helper\Session;
use \KoKoP\Helper\Auth;
use \KoKoP\Helper\AuthAllowAll;
use \KoKoP\Helper\CachableRequest;
use \KoKoP\Helper\MemoryCache;
use \KoKoP\Helper\Request;
use \KoKoP\Helper\Cookie;
use \KoKoP\Helper\Organization;
use \KoKoP\Interfaces\AbstractCache;
use \KoKoP\Interfaces\AbstractRequest;
use \KoKoP\Interfaces\AbstractAuth;
use \KoKoP\Interfaces\AbstractSecure;
use \KoKoP\Interfaces\AbstractServices;
use \KoKoP\Interfaces\AbstractSession;
use \KoKoP\Interfaces\AbstractConfig;
use \KoKoP\Interfaces\AbstractOrganization;

class RuntimeServices implements AbstractServices
{
    private AbstractAuth $auth;
    private AbstractCache $cache;
    private AbstractConfig $config;
    private AbstractRequest $request;
    private AbstractSecure $secure;
    private AbstractSession $session;
    private AbstractOrganization $organization;

    public function __construct(AbstractConfig $config)
    {
        $this->config = $config;
        $this->secure = new Secure($this->getConfigService());
        $this->session = new Session($this->getConfigService(), $this->secure, new Cookie());

        $this->cache = $this->getConfigService()->getValue('PREDIS') ?
            new RedisCache($this->getConfigService(), $this->secure) :
            new MemoryCache($this->secure);

        $this->request = new CachableRequest($this->cache, new Request());

        $this->auth = $this->getConfigService()->getValue('MS_AUTH', false) ?
            new Auth($this->getConfigService(), $this->request) :
            new AuthAllowAll($this->getConfigService());

        $this->organization = new Organization($this->getConfigService());
    }

    public function getRequestService(): AbstractRequest
    {
        return $this->request;
    }

    public function getCacheService(): AbstractCache
    {
        return $this->cache;
    }

    public function getSessionService(): AbstractSession
    {
        return $this->session;
    }

    public function getAuthService(): AbstractAuth
    {
        return $this->auth;
    }

    public function getSecureService(): AbstractSecure
    {
        return $this->secure;
    }

    public function getConfigService(): AbstractConfig
    {
        return $this->config;
    }

    public function getOrganizationService(): AbstractOrganization
    {
        return $this->organization;
    }
}
