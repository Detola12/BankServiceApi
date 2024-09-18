<?php

namespace App\Services;

use App\Contracts\UserServiceInterface;
use App\Dtos\UserDto;
use App\Http\Requests\CreateUserRequest;
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
     *
     */
    public function register(UserDto $userDto): BaseResponse
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
            $token = $user->createToken($userDto->getEmail(), ['*'], now()->addMinutes(10))->plainTextToken;

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
     *
     */
    public function login(string $email, string $password): BaseResponse
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
            $token = $user->createToken($email, ['*'], now()->addMinutes(10))->plainTextToken;
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
     * @param int $id
     * @return UserDto|Model
     */
    public function getUserById(int $id): UserDto|Model
    {
        // TODO: Implement getUserById() method.
    }

    /**
     * @return BaseResponse
     */
    public function logout(Request $request): BaseResponse
    {
    $request->user()->tokens()->delete();
        $response = new BaseResponse();
        $response->setSuccess(true);
        $response->setMessage('Logout Successful');

        return $response;
    }
}
