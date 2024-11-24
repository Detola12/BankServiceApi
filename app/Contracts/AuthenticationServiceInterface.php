<?php

namespace App\Contracts;

use App\Dtos\UserDto;
use App\Responses\UserResponse;
use Symfony\Component\HttpFoundation\Request;

interface AuthenticationServiceInterface
{
    public function register(UserDto $userDto) : UserResponse;

    public function login(string $email, string $password) : UserResponse;

    public function logout(Request $request) : UserResponse ;
}
