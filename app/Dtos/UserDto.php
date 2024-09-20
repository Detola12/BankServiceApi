<?php

namespace App\Dtos;

use App\Contracts\UserDtoInterface;
use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use phpDocumentor\Reflection\Types\Collection;

class UserDto implements UserDtoInterface
{
    private string $firstName;
    private string $lastName;
    private string $email;
    private string $phone;
    private string $password;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * @param string $firstName
     */
    public function setFirstName(string $firstName): void
    {
        $this->firstName = $firstName;
    }

    /**
     * @return string
     */
    public function getFirstName(): string
    {
        return $this->firstName;
    }

    /**
     * @param string $lastName
     */
    public function setLastName(string $lastName): void
    {
        $this->lastName = $lastName;
    }

    /**
     * @return string
     */
    public function getLastName(): string
    {
        return $this->lastName;
    }

    /**
     * @param string $email
     */
    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }

    /**
     * @param string $phone
     */
    public function setPhone(string $phone): void
    {
        $this->phone = $phone;
    }

    /**
     * @return string
     */
    public function getPhone(): string
    {
        return $this->phone;
    }

    /**
     * @param string $password
     */
    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    /**
     * @param CreateUserRequest $request
     * @return self
     */
    public static function FromCreateRequestToModel(CreateUserRequest $request): self
    {
        $userDto = new UserDto();
        $userDto->setFirstName($request->first_name);
        $userDto->setLastName($request->last_name);
        $userDto->setEmail($request->email);
        $userDto->setPhone($request->phone);
        $userDto->setPassword($request->password);
        return $userDto;

    }

    /**
     * @param Model $userModel
     * @return array|Collection
     */
    public static function FromModelToArray(Model $userModel): array|Collection
    {
        return [
            'first_name' => $userModel->first_name,
            'last_name' => $userModel->last_name,
            'email' => $userModel->email,
            'phone' => $userModel->phone
        ];
    }


    /**
     * @param LoginRequest $request
     * @return array
     */
    public static function FromLoginRequestToArray(LoginRequest $request): array
    {
        return [
            'email' => $request->email,
            'password' => $request->password
        ];
    }


}
