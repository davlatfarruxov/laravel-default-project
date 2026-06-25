<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // ── Manager demo ──
        $manager = User::firstOrCreate(
            ['email' => 'manager@marketingpro.uz'],
            ['name' => 'Manager Demo', 'password' => Hash::make('password')]
        );
        $manager->syncRoles(['manager']);

        // ── Oddiy foydalanuvchilar (demo) ──
        $regulars = [
            ['name' => 'Aziz Karimov',   'email' => 'aziz@marketingpro.uz'],
            ['name' => 'Dilnoza Yusupova','email' => 'dilnoza@marketingpro.uz'],
            ['name' => 'Bobur Aliyev',    'email' => 'bobur@marketingpro.uz'],
        ];

        foreach ($regulars as $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                ['name' => $data['name'], 'password' => Hash::make('password')]
            );
            $user->syncRoles(['user']);
        }

        $this->command?->info('✓ Demo users seeded (manager + 3 users). Parol: password');
    }
}
