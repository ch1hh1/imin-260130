<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = ['管理者', '編集者', '閲覧者', '一般利用者'];
        foreach ($roles as $name) {
            Role::firstOrCreate(['name' => $name]);
        }
    }
}
