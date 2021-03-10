<?php


namespace App\Http\Helpers;


class ArrayHelper
{
    /**
     * @param $array
     * @return bool
     */
    public static function arrayIsset($array): bool
    {
        $isset = true;
        foreach ($array as $item) {
            if (!isset($item)) {
                $isset = false;
            }
        }
        return $isset;
    }
}
