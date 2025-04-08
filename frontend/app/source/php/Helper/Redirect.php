<?php

declare(strict_types=1);

namespace KoKoP\Helper;

class Redirect
{
  public function __construct($location, $query = [])
  {

    if (is_array($query) && !empty($query)) {
      $queryString = "?" . http_build_query($query);
    } else {
      $queryString = "";
    }

    header("Location: " . $location . $queryString);
    exit;
  }
}
