<?php


namespace App\Replacer;


class Null implements Replacer
{

    public function replace($text, $data)
    {
        return '';
    }
}