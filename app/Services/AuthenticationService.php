<?php

namespace App\Services;

use App\Contracts\AuthenticationServiceInterface;
use App\Dtos\UserDto;
use App\Models\User;
use App\Notifications\UserCreated;
use App\Responses\UserResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class AuthenticationService implements AuthenticationServiceInterface
{
    /**
     * @param UserDto $userDto
     * @return UserResponse
     */
    public function register(UserDto $userDto): UserResponse
    {
        $response = new UserResponse();
        try {
            DB::beginTransaction();
            $user = User::create([
                'first_name' => $userDto->getFirstName(),
                'last_name' => $userDto->getLastName(),
                'email' => $userDto->getEmail(),
                'phone' => $userDto->getPhone(),
                'password' => $userDto->getPassword()
            ]);
            Auth::login($user);
            $user->notify(new UserCreated($user));
            $token = $user->createToken($userDto->getEmail(), ['*'], now()->addHour())->plainTextToken;

            $response->setSuccess(true);
            $response->setMessage('User Created Successfully');
            $response->setData(['token' => $token]);
            DB::commit();
            return $response;
        }

        catch (\Exception $exception){
            Log::error('Something went wrong: ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            DB::rollBack();
            return $response;
        }
    }

    /**
     * @param string $email
     * @param string $password
     * @return UserResponse
     */
    public function login(string $email, string $password): UserResponse
    {
        $response = new UserResponse();
        try {
            $user = User::where('email', $email)->first();

            if (!$user || !Hash::check($password, $user->password)) {
                $response->setSuccess(false);
                $response->setMessage('Invalid Credentials');
                return $response;
            }
            Auth::attempt(['email' => $email,'password' => $password]);
            $token = $user->createToken($email, ['*'], now()->addHour())->plainTextToken;
            $response->setSuccess(true);
            $response->setMessage('Login Successfully');
            $response->setData(['token' => $token]);

            return $response;
        }
        catch (\Exception $exception){
            Log::error('Something went wrong: ' . $exception);
            $response->setSuccess(false);
            $response->setMessage('Something went wrong');
            return $response;
        }

    }

    /**
     * @param Request $request
     * @return UserResponse
     */
    public function logout(Request $request): UserResponse
    {
        $request->user()->tokens()->delete();
        $response = new UserResponse();
        $response->setSuccess(true);
        $response->setMessage('Logout Successful');

        return $response;
    }
}
