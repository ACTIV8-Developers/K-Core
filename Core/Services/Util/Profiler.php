<?php

namespace App\Services\Util;

class Profiler
{
    public static function memoryUsage()
    {
        return self::convert(memory_get_usage(true));
    }

    private static function convert($size)
    {
        $unit = array('b','kb','mb','gb','tb','pb');
        return @round($size/pow(1024, ($i = floor(log($size,1024)))),2) . ' '.$unit[$i];
    }
}