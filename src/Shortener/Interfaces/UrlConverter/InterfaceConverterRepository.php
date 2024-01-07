<?php

namespace App\Shortener\Interfaces\UrlConverter;

interface InterfaceConverterRepository
{

    /**
     * @param string $code
     * @param string $url
     * @param int $userId
     * @return bool
     */
    public function saveAll(string $code, string $url, int $userId): bool;


    /**
     * @param string $code
     * @param int $userId
     * @return string
     */
    public function getUrl(string $code, int $userId): string;


    /**
     * @param string $url
     * @param int $userId
     * @return string
     */
    public function getCode(string $url, int $userId): string;
}