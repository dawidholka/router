<?php

namespace App\Actions\Users;

use App\DTOs\UserData;
use App\Models\User;

class CreateUser
{
    public function execute(UserData $data): User
    {
        $user = new User();
        $user->name = $data->name;
        $user->email = $data->email;
        $user->password = $data->password;
        $user->admin = $data->admin;

        $user->save();

        return $user;
    }
}
