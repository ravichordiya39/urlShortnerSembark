<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;


class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
         DB::insert("
            INSERT INTO users 
            (
                name,
                email,
                password,
                 created_at,
                updated_at
            )
            VALUES (?, ?,  ?, NOW(), NOW())
        ", [
            'SuperAdmin',
            'super@admin.com',
            Hash::make('12345678')
        ]);


        $userId = DB::getPdo()->lastInsertId();


        // Get SuperAdmin Role
        $roleId = DB::table('tbl_roles')
            ->where('title','Superadmin')
            ->value('id');


        // Assign Role
        DB::insert("
            INSERT INTO tbl_role_user
            (
                user_id,
                role_id,
                created_at,
                updated_at
            )
            VALUES (?, ?, NOW(), NOW())
        ", [
            $userId,
            $roleId
        ]);

        $permissions = DB::table('tbl_permissions')
            ->pluck('id');

        $createShortUrlPermission = DB::table('tbl_permissions')
            ->where('title','short_url_add')
            ->value('id');

        $insertData = [];

        foreach ($permissions as $permissionId) {
            if($permissionId != $createShortUrlPermission){

                $insertData[] = [
                    'permission_id' => $permissionId,
                    'role_id' => $roleId
                ];
            }
        }

        if (!empty($insertData)) {
            DB::table('tbl_permission_role')->insert($insertData);
        }
    }
}
