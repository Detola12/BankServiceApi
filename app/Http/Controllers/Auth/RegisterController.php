<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function index(CreateUserRequest $request){

        $userDto = UserDto::FromCreateRequestToModel($request);
        $response = $this->userService->register($userDto);
        return $response->compose();
    }
}
