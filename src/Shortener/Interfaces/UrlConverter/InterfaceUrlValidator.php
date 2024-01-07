<?php

namespace App\Shortener\Interfaces\UrlConverter;

interface InterfaceUrlValidator
{
    /**
     * @param string $url
     * @return string
     */
    public function validation(string $url): int;
}