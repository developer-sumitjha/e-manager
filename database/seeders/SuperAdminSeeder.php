<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SuperAdmin;

class SuperAdminSeeder extends Seeder
{
    public function run()
    {
        SuperAdmin::updateOrCreate(
            ['email' => 'admin@emanager.com'],
            [
                'name' => 'Platform Administrator',
                'password' => bcrypt('SuperAdmin@123'),
                'phone' => '+977-1-1234567',
                'role' => 'super_admin',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Super Admin created');
        $this->command->info('📧 Email: admin@emanager.com');
        $this->command->info('🔑 Password: SuperAdmin@123');
    }
}
