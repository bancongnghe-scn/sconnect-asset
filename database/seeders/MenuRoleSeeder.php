<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuRoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'role_id' => '1',
                'menu_id' => '1',
            ],
            [
                'role_id' => '1',
                'menu_id' => '2',
            ],
            [
                'role_id' => '1',
                'menu_id' => '3',
            ],
            [
                'role_id' => '1',
                'menu_id' => '4',
            ],
        ];
        DB::table('menu_roles')->insert($data);
    }
}
