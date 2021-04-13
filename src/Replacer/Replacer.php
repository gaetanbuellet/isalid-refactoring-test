<?php


namespace App\Replacer;


interface Replacer
{
    /**
     * @param $propertyName
     * @param array $data
     * @return string
     */
    public function replace($propertyName, array $data);
}
