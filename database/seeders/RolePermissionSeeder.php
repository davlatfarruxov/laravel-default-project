<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

final class RolePermissionSeeder extends Seeder
{
    // ── Platform (Super Admin only) ───────────────────────────────────────────
    private const PLATFORM_PERMISSIONS = [
        'dashboard'  => ['view'],
        'roles'         => ['view', 'create', 'edit', 'delete', 'assign'],
        'users'         => ['view', 'create', 'edit', 'delete'],
    ];

    // ── Marketing (v2.0) ──────────────────────────────────────────────────────
    private const MARKETING_PERMISSIONS = [
        'leads'      => ['view', 'create', 'edit', 'delete'],
        'campaigns'  => ['view', 'create', 'edit', 'delete'],
        'analytics'  => ['view'],
    ];

    // ── Manager: marketing access, no platform/delete ─────────────────────────
    private const MANAGER_PERMISSIONS = [
        'dashboard'  => ['view'],
        'leads'      => ['view', 'create', 'edit'],
        'campaigns'  => ['view'],
        'analytics'  => ['view'],
    ];

    public function run(): void
    {
        app()['cache']->forget(config('permission.cache.key'));

        // ── 1. Platform + Marketing permissions → SuperAdmin ──────────────────
        $platformNames  = $this->createPermissions(self::PLATFORM_PERMISSIONS);
        $marketingNames = $this->createPermissions(self::MARKETING_PERMISSIONS);
        $allNames       = array_merge($platformNames, $marketingNames);

        $superadmin = Role::firstOrCreate(['name' => 'superadmin', 'guard_name' => 'web']);
        $superadmin->syncPermissions($allNames);

        // ── 3. Manager ────────────────────────────────────────────────────────
        $managerNames = $this->createPermissions(self::MANAGER_PERMISSIONS);
        $manager = Role::firstOrCreate(['name' => 'manager', 'guard_name' => 'web']);
        $manager->syncPermissions($managerNames);

        // ── 7. Default SuperAdmin user ────────────────────────────────────────
        $adminUser = User::withoutGlobalScopes()->firstOrCreate(
            ['email' => 'admin@marketingpro.uz'],
            [
                'name'     => 'Super Admin',
                'password' => bcrypt('B7654321'),
            ]
        );
        $adminUser->syncRoles(['superadmin']);

        $this->command?->info('✓ Permissions and roles seeded.');
        $this->command?->info('  superadmin  → ' . count($allNames) . ' permissions');
        $this->command?->info('  manager     → ' . count($managerNames) . ' permissions');
        $this->command?->info('  Login: admin@marketingpro.uz / B7654321');
    }

    private function createPermissions(array $config): array
    {
        $names = [];
        foreach ($config as $resource => $actions) {
            foreach ($actions as $action) {
                $perm    = Permission::firstOrCreate(['name' => "{$resource}.{$action}", 'guard_name' => 'web']);
                $names[] = $perm->name;
            }
        }
        return $names;
    }
}
