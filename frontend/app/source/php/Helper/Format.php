<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use DateTime;

class Format
{

  /* Social Security number */
  public static function socialSecuriyNumber(string $number)
  {
    if (strlen($number) == 12) {
      return substr($number, 0, 8) . "-" . substr($number, 8, 12);
    }
    return false;
  }

  /* Get the sex from a social security numer */
  public static function sex($number, $readable = false)
  {
    $number = Sanitize::number($number);
    if (strlen($number) == 12) {
      $sexDigit = substr($number, 10, 1);
      return ($sexDigit % 2 == 0) ? ($readable ? 'Kvinna' : 'F') : ($readable ? 'Man' : 'M');
    }
    return false;
  }

  /* Age */
  public static function getCurrentAge($pnr)
  {
    return (new DateTime(substr($pnr, 0, 4) . "-" . substr($pnr, 4, 2) . "-" . substr($pnr, 6, 2)))->diff(
      new DateTime('today')
    )->y;
  }

  /* PostalCode */
  public static function postalCode($postalCode)
  {
    return trim(substr($postalCode, 0, 3) . " " . substr($postalCode, 3, 2));
  }

  /* MunicipalityCode */
  public static function municipalityCode($municipalityCode)
  {

    $codes = [
      '25' => 'Helsingborg',
      '84' => 'Höganäs'
    ];

    if (isset($codes[$municipalityCode])) {
      return $codes[$municipalityCode] . " (" . $municipalityCode . ")";
    }

    return $municipalityCode;
  }

  /* Capitalized Word */
  public static function capitalize($string)
  {
    return mb_convert_case(mb_strtolower($string), MB_CASE_TITLE, "UTF-8");
  }

  /**
   * Convert the given data to an associative array.
   *
   * This method uses JSON encoding and decoding to convert the data to an array.
   *
   * @param mixed $data The data to be converted.
   *
   * @return array|null The converted data as an associative array, or null on failure.
   */
  public static function convertToArray($data)
  {
    return json_decode(json_encode($data), true);
  }

  /**
   * Format the given date to the specified format.
   *
   * This method uses the `date` function to format the given date to the specified format.
   *
   * @param string $date The date to be formatted.
   * @param string $format The format to be used for the date.
   *
   * @return string The formatted date.
   */
  public static function date($date, $format = 'Y-m-d')
  {
    if (is_null($date) || empty($date) || !is_numeric($date)) {
      return "";
    }
    return date($format, strtotime($date));
  }

  /**
   * Adds parentheses around a given string.
   *
   * @param string $string The string to add parentheses to.
   * @return string The modified string with parentheses added.
   */
  public static function addPharanthesis($string)
  {
    if (empty($string)) {
      return "";
    }
    return " (" . $string . ")";
  }
}
