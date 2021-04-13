<?php


namespace App\Replacer;


class Null implements Replacer
{

    public function replace($propertyName, array $data)
    {
        return '';
    }
}
