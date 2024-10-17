<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name'      => 'Vai trò',
                'icon'      => 'fa fa-home',
                'order'     => '1',
                'url'       => '/rbac/role/list',
                'parent_id' => '1',
            ],
            [
                'name'      => 'Quyền',
                'icon'      => 'fa fa-home',
                'order'     => '2',
                'url'       => '/rbac/permission/list',
                'parent_id' => '1',
            ],
            [
                'name'      => 'Menu',
                'icon'      => 'fa fa-home',
                'order'     => '3',
                'url'       => '/rbac/menu/list',
                'parent_id' => '1',
            ],
        ];
        DB::table('menus')->insert([
            'name'  => 'Cài đặt',
            'icon'  => 'fa fa-home',
            'order' => '1',
        ]);
        DB::table('menus')->insert($data);
    }
}
