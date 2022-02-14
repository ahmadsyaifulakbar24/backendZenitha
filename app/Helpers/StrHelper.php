<?php

namespace App\Helpers;

use Illuminate\Support\Str;
class StrHelper {

    public static function sort_character($string)
    {
        $stringParts = str_split($string);
        sort($stringParts);
        return implode($stringParts);
    }
    
}