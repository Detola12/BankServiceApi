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
    public function getUserById(int $id) : UserResponse;

    public function getAllUsers(int $size) : UserResponse;
}
