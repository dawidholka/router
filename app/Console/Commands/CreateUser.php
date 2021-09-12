<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class CreateUser extends Command
{
    protected $signature = 'user:create';

    protected $description = 'Create a new user';

    public function handle(): int
    {
        $name = $this->ask('Name?');
        $email = $this->ask('Email?');
        $pwd = $this->secret('Password?');

        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($pwd),
        ]);

        $this->info('User account created for ' . $name);

        return 0;
    }
}
