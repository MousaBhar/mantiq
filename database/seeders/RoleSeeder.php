<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Seed the application's roles and assign them to users.
     */
    public function run(): void
    {
        DB::transaction(function () {

            // Create or update the Admin role.
            $adminRole = Role::updateOrCreate(
                ['slug' => 'admin'],
                [
                    'name' => 'Administrator',
                    'description' => 'Full access to manage the application.',
                ]
            );

            // Create or update the Registered User role.
            $userRole = Role::updateOrCreate(
                ['slug' => 'user'],
                [
                    'name' => 'Registered User',
                    'description' => 'Access to standard application features.',
                ]
            );

            // Assign the Admin role to the configured admin account.
            $adminEmail = config('mantiq.admin_email');

            if ($adminEmail) {
                User::where('email', $adminEmail)->update([
                    'role_id' => $adminRole->id,
                ]);
            }

            // Assign the default User role to users without a role.
            User::whereNull('role_id')->update([
                'role_id' => $userRole->id,
            ]);
        });
    }
}
