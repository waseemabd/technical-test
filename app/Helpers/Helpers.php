<?php


namespace App\Helpers;


use Illuminate\Support\Str;
use phpDocumentor\Reflection\Types\Array_;
use function PHPSTORM_META\map;

class Helpers
{
    function toCamel($o)
    {
        if (is_array($o)) {
            return array_map(function ($value) {
                if (is_object($value)) {
                    $value = $this->toCamel($value);
                }
                return $value;
            }, $o);
        } else {
            $newO = [];
            foreach ($o as $origKey) {
                if (array_key_exists($origKey, $o)) {
                    $newKey = Str::camel($origKey);
                    $value = $o[$origKey];
                    if (is_array($value) || ($value !== null && is_object($value))) {
                        $value = $this->toCamel($value);
                    }
                    $newO[$newKey] = $value;
                }
            }
        }
        return $newO;
    }
}
