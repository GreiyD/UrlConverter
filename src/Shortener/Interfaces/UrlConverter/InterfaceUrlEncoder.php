<?php

namespace App\Shortener\Interfaces\UrlConverter;

interface InterfaceUrlEncoder
{


    /**
     * @param string $url
     * @return string
     */
    public function encode(string $url): string;
}