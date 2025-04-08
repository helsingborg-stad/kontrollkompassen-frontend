<?php

declare(strict_types=1);

namespace KoKoP\Helper;

use HelsingborgStad\GlobalBladeService\GlobalBladeService;
use ComponentLibrary\Init as ComponentLibraryInit;

/* The `class Enviroment` in the provided PHP code is a helper class that contains methods related to
checking and loading a component library using the Blade template engine. Here is a summary of what
the class is doing: */

class Enviroment
{
    private static $loader = BASEPATH . "vendor/helsingborg-stad/component-library/load.php";

    /**
     * The function checks if a component library is installed by verifying the existence of a specific
     * file.
     * 
     * @return bool a boolean value, either `true` if the component library is installed (if the file
     * specified by `self::` exists) or `false` if it is not installed.
     */
    public static function componentLibraryIsInstalled(): bool
    {
        if (file_exists(self::$loader)) {
            return true;
        }
        return false;
    }
}
