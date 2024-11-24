<?php

namespace App\Http\Controllers\Auth;

use App\Dtos\UserDto;
use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\AuthenticationService;
use App\Services\UserService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SessionController extends Controller
{
    public function __construct(private readonly AuthenticationService $authenticationService)
    {
    }

    public function login(LoginRequest $request)
    {
        $credentials = UserDto::FromLoginRequestToArray($request);
        $response = $this->authenticationService->login($credentials['email'], $credentials['password']);
        return $response->compose();
    }

    public function logout(Request $request)
    {
        $response = $this->authenticationService->logout($request);
        return $response->compose();
    }


}
