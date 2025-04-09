<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use \KoKoP\Enums\AuthErrorReason;

class AuthException extends \Exception
{
    function __construct(AuthErrorReason $reason)
    {
        match ($reason) {
            AuthErrorReason::HttpError => parent::__construct(
                "Authenticeringsservern svarade inte",
                AuthErrorReason::HttpError->value
            ),
            AuthErrorReason::InvalidCredentials => parent::__construct(
                "Felaktig användarinformation",
                AuthErrorReason::InvalidCredentials->value
            ),
            AuthErrorReason::Unauthorized => parent::__construct(
                "Ej auktoriserad",
                AuthErrorReason::Unauthorized->value
            ),
        };
    }
}
