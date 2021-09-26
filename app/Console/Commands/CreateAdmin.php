<?php

namespace App\Console\Commands;

use App\DTOs\UserData;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateAdmin extends Command
{
    protected $signature = 'admin:create';

    protected $description = 'Create a new admin user';

    public function handle(\App\Actions\Users\CreateUser $createUser): int
    {
        $name = $this->ask('Name?');
        $email = $this->ask('Email?');
        $pwd = $this->secret('Password?');

        $createUser->execute(new UserData([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($pwd),
            'admin' => true
        ]));

        $this->info('Admin account created for ' . $name);

        return 0;
    }
}
