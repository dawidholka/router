<?php

namespace App\Actions\Users;

use App\DTOs\UserData;
use App\Models\User;

class UpdateUser
{
    public function execute(User $user, UserData $data)
    {
        $user->name = $data->name;
        $user->email = $data->email;
        if ($data->password) {
            $user->password = $data->password;
        }
        $user->admin = $data->admin;

        $user->save();

        return $user;
    }
}
