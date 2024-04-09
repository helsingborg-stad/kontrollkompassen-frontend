<?php

declare(strict_types=1);

namespace NavetSearch\Interfaces;

interface AbstractServices
{
    public function getRequestService(): AbstractRequest;
    public function getCacheService(): AbstractCache;
    public function getSessionService(): AbstractSession;
    public function getAuthService(): AbstractAuth;
    public function getSecureService(): AbstractSecure;
    public function getConfigService(): AbstractConfig;
    public function getSearchService(): AbstractSearch;
}
