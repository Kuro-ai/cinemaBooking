<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use Carbon\Carbon;

class UnbanExpiredUsers extends Command
{
    protected $signature = 'users:unban-expired';
    protected $description = 'Unban users whose ban duration has expired';

    public function handle()
    {
        $users = User::whereNotNull('banned_until')
            ->where('banned_until', '<', Carbon::now('Asia/Yangon'))
            ->update(['banned_until' => null]);

        $this->info("$users users have been unbanned.");
    }
}
