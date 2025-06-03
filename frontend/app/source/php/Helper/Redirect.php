<?php

declare(strict_types=1);

namespace KoKoP\Helper;

class Redirect
{
  public function __construct($location, $query = [])
  {
    $queryString = '';

    if (!empty($query)) {
      $queryString = '?' . http_build_query($query);
    }

    header('Location: ' . $location . $queryString);
    exit;
  }
}
