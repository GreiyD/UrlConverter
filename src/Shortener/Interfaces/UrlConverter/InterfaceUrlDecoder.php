<?php

namespace App\Shortener\Interfaces\UrlConverter;

interface InterfaceUrlDecoder
{
    /**
     * @param string $code
     * @return string
     * @throws \InvalidArgumentException
     */
    public function decode(string $code): string;
}