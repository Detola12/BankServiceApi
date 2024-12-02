<?php

namespace App\Services;

use App\Contracts\UserServiceInterface;
use App\Dtos\UserDto;
use App\Http\Requests\CreateUserRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use App\Notifications\UserCreated;
use App\Responses\BaseResponse;
use App\Responses\UserResponse;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\Request;

class UserService implements UserServiceInterface
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param int $id
     * @return UserResponse
     */
    public function getUserById(int $id): UserResponse
    {
        $user = User::where('id', $id)->first();
        $response = new UserResponse();
        if(!$user){
            $response->setSuccess(false);
            $response->setMessage('User not found');
            $response->setCode(404);
            return $response;
        }

        $response->setSuccess(true);
        $response->setMessage('User details fetched');
        $userDto = UserDto::FromModelToArray($user);
        $response->setData(['user' => $userDto]);
        return $response;
    }

    public function getAllUsers(int $size = 10) : UserResponse
    {
        $user = User::query()->paginate($size);
        $response = new UserResponse();

        $response->setSuccess(true);
        $response->setMessage('Users detail fetched');
        $userDto = UserResource::collection($user);
        $response->setData(['user' => $userDto]);
        return $response;
    }
}
