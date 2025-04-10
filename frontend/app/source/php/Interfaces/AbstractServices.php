<?php

declare(strict_types=1);

namespace KoKoP\Interfaces;

interface AbstractServices
{
    public function getRequestService(): AbstractRequest;
    public function getCacheService(): AbstractCache;
    public function getSessionService(): AbstractSession;
    public function getAuthService(): AbstractAuth;
    public function getSecureService(): AbstractSecure;
    public function getConfigService(): AbstractConfig;
    public function getChechOrgNoService(): AbstractCheckOrgNo;
}
