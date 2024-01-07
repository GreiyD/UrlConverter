<?php

namespace App\Shortener\Helpers\Validation;

use App\Shortener\Interfaces\UrlConverter\InterfaceUrlValidator;

class UrlValidator implements InterfaceUrlValidator
{

    /**
     * @param string $url
     * @return string
     */
    public function validation(string $url): int
    {
        if (file_get_contents($url) && filter_var($url, FILTER_VALIDATE_URL)) {
            return http_response_code(200);
        } else {
            return http_response_code(400);
        }
    }
}