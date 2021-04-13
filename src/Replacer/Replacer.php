<?php


namespace App\Replacer;


interface Replacer
{
    public function replace($text, $data);
}
