<?php

declare(strict_types=1);

namespace KoKoP\Utils;

function encodeRFC7230(string $input): string
{
    if ($input === '') {
        return '';
    }

    $encoded = '';
    $allowedPattern = "@^[ \t\x21-\x7E\x80-\xFF]*$@D";

    for ($i = 0; $i < strlen($input); $i++) {
        $char = $input[$i];
        $ascii = ord($char);

        if (preg_match($allowedPattern, (string) $char) === 1) {
            $encoded .= $char;
        } else {
            $encoded .= sprintf('%%%02X', $ascii);
        }
    }

    return $encoded;
}
