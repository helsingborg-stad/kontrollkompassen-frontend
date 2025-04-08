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
                (int)AuthErrorReason::HttpError
            ),
            AuthErrorReason::InvalidCredentials => parent::__construct(
                "Felaktig användarinformation",
                (int)AuthErrorReason::InvalidCredentials
            ),
            AuthErrorReason::Unauthorized => parent::__construct(
                "Ej auktoriserad",
                (int)AuthErrorReason::Unauthorized
            ),
        };
    }
}
