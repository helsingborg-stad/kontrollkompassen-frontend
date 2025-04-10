<?php

declare(strict_types=1);

namespace KoKoP\Helper;

class Sanitize
{

  public static function number($string)
  {
    return preg_replace('/[^0-9.]+/', '', (string) $string);
  }

  public static function string(mixed $string)
  {
    if (is_string($string)) {
      return $string;
    }

    // Resembles a bool
    if ($string == 1 || $string == 0) {
      return "";
    }

    // Anything else, empty string
    return "";
  }

  /** Santitize password to comply with active directory issues */
  public static function password($password)
  {
    $password = stripslashes($password);
    $password = preg_replace('/(["\/\\\])/', '\\\\$1', $password);
    return $password;
  }
}
