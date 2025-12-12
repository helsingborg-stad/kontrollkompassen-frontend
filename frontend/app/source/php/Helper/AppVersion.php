<?php

declare(strict_types=1);

namespace KoKoP\Helper;

class AppVersion
{
    public static function getAppVersion(): string
    {
        $versionFile = dirname(__DIR__, 3) . '/data/app_version.txt';
        $appVersion = 'unknown';
        if (is_readable($versionFile)) {
            $contents = @file_get_contents($versionFile);
            if ($contents !== false) {
                $trimmed = trim($contents);
                if ($trimmed !== '') {
                    $appVersion = $trimmed;
                }
            }
        }
        return $appVersion;
    }
}
