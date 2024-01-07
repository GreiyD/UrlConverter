<?php

namespace App\Shortener\Interfaces\User;

interface InterfaceUserRepository
{

    /**
     * @param string $nickname
     * @param string $email
     * @param string $password
     * @return bool
     */
    public function save(string $nickname, string $email, string $password): bool;
}