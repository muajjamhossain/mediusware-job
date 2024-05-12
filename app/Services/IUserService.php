<?php

namespace App\Services;

interface IUserService
{
    public function createUser(array $attr): ?array;
}
