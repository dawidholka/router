<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AfterAppUpdate extends Command
{
    protected $signature = 'router:after-app-update';

    protected $description = 'Run commands after application has been updated';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->call('view:clear');
        $this->call('cache:clear');
        $this->call('config:clear');
        $this->call('migrate', ['--force' => true]);
        return 0;
    }
}
