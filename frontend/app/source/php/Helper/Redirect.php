<?php

declare(strict_types=1);

namespace KoKoP\Helper;

class Redirect
{
  public function __construct($location, $query = [])
  {
    $queryString = !empty($query) ? '?' . http_build_query($query) : '';

    header('Location: ' . $location . $queryString);
    exit;
  }
}
