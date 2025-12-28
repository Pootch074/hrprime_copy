<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RolesSeeder extends Seeder
{
    public function run()
    {
        $roles = [
            'HR-PLANNING',
            // Add more roles as needed
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate([
                'name' => $role,
                'guard_name' => 'web', // make sure this matches your user guard
            ]);
        }

        $this->command->info('Roles seeded successfully!');
    }
}
