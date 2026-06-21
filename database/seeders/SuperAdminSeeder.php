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
                status,
                created_at,
                updated_at
            )
            VALUES (?, ?, ?, ?, NOW(), NOW())
        ", [
            'SuperAdmin',
            'super@admin.com',
            Hash::make('12345678'),
            'Y'
        ]);


        $userId = DB::getPdo()->lastInsertId();


        // Get SuperAdmin Role
        $roleId = DB::table('roles')
            ->where('title','Superadmin')
            ->value('id');


        // Assign Role
        DB::insert("
            INSERT INTO role_user
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

    }
}
