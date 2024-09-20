<?php

namespace App\Contracts;

use App\Dtos\UserDto;
use App\Http\Requests\CreateUserRequest;
use App\Responses\BaseResponse;
use App\Responses\UserResponse;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

interface UserServiceInterface
{
    public function register(UserDto $userDto) : UserResponse;

    public function login(string $email, string $password) : UserResponse;

    public function logout(Request $request) : UserResponse ;

    public function getUserById(int $id) : UserResponse;
}
