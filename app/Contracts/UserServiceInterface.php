<?php

namespace App\Contracts;

use App\Dtos\UserDto;
use App\Http\Requests\CreateUserRequest;
use App\Responses\BaseResponse;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpFoundation\Request;

interface UserServiceInterface
{
    public function register(UserDto $userDto) : BaseResponse;

    public function login(string $email, string $password) : BaseResponse;

    public function logout(Request $request) : BaseResponse ;

    public function getUserById(int $id) : UserDto | Model;
}
