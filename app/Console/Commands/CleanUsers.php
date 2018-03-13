<?php

namespace App\Console\Commands;

use App\User;
use Illuminate\Support\Carbon;
use Illuminate\Console\Command;

class CleanUsers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'clean-users';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Cleans unconfirmed users';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        User::whereNotNull('confirmation_token')
            ->where('created_at', '<', Carbon::now()->subMonth())
            ->delete();
    }
}
