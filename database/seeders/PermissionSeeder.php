<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Permission;


class PermissionSeeder extends Seeder
{
    public function run()
    {
        $permissions = [
            'Manajemen Berita',
            'Manajemen Tenant',
            'Manajemen Sewa Lahan',
            'Manajemen Perijinan Usaha',
            'Manajemen Pengiklanan',
            'Manajemen Field Trip',
            'Manajemen Laporan Keuangan',
            'Manajemen Slider',
        ];

        foreach ($permissions as $item) {
            $permission = Permission::create([
                'permission_name' => $item,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }
}
