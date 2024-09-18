<?php

namespace App\Contracts;

use App\Http\Requests\CreateUserRequest;
use App\Http\Requests\LoginRequest;
use Illuminate\Database\Eloquent\Model;
use phpDocumentor\Reflection\Types\Collection;

interface UserDtoInterface
{
    public static function FromCreateRequestToModel(CreateUserRequest $request) : self;

    public static function FromLoginRequestToArray(LoginRequest $request) : array;

    public function FromModelToArray(Model $userModel) : array | Collection;
}
