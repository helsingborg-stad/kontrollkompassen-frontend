<?php

declare(strict_types=1);

namespace KoKoP\Helper;

class Format
{
  public static function capitalize($string)
  {
    return mb_convert_case(mb_strtolower($string), MB_CASE_TITLE, "UTF-8");
  }

  public static function convertToArray($data)
  {
    return json_decode(json_encode($data), true);
  }

  public static function date($date, $format = 'Y-m-d')
  {
    if (is_null($date) || empty($date) || !is_numeric($date)) {
      return "";
    }
    return date($format, strtotime($date));
  }

  public static function addPharanthesis($string)
  {
    if (empty($string)) {
      return "";
    }
    return " (" . $string . ")";
  }
}
