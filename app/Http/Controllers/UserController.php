<?php

namespace App\Http\Controllers;

use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct(private UserService $userService)
    {
    }

    public function getUserById($user)
    {
        $response = $this->userService->getUserById($user);
        return $response->compose();
    }
}
