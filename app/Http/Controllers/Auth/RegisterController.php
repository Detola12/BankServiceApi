<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\CreateUserRequest;
use App\Services\AuthenticationService;

class RegisterController extends Controller
{
    public function __construct(private readonly AuthenticationService $authenticationService)
    {
    }

    public function index(CreateUserRequest $request)
    {
        $userDto = UserDto::FromCreateRequestToModel($request);
        $response = $this->authenticationService->register($userDto);
        return $response->compose();
    }
}
