<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;

class SyncUserRoles extends Command
{
    protected $signature = 'roles:sync';
    protected $description = 'Sync users.role column to Spatie roles';

    public function handle()
    {
        $users = User::whereNotNull('role')->get();

        foreach ($users as $user) {
            $user->syncRoles([$user->role]);
            $this->info("Synced roles for user: {$user->id} ({$user->role})");
        }

        $this->info('All roles synced successfully!');
    }
}
