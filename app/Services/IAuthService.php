<?php

namespace App\Services;

interface IAuthService
{
    public function login(array $attr): ?array;
}
