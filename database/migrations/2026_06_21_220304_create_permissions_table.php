<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_permissions', function (Blueprint $table) {

            $table->increments('id');

            $table->string('title')
                ->nullable();

            $table->string('slug')
                ->unique();

            $table->enum('status',['Y','N'])
                ->default('Y');

            $table->timestamp('created_at')
                ->useCurrent()
                ->nullable();

            $table->timestamp('updated_at')
                ->useCurrent()
                ->nullable();

            $table->timestamp('deleted_at')
                ->nullable();

        });


        // Insert default permissions
        DB::table('tbl_permissions')->insert([

            [
                'title' => 'user_access',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'user_list',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'user_view',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'user_add',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'user_edit',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'user_delete',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],

             [
                'title' => 'permission_access',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'permission_list',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'permission_view',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'permission_add',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'permission_edit',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'permission_delete',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],

             [
                'title' => 'role_access',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'role_list',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'role_view',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'role_add',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'role_edit',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'role_delete',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],

             [
                'title' => 'company_access',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'company_list',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'company_view',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'company_add',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'company_edit',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'company_delete',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],

             [
                'title' => 'short_url_access',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'short_url_list',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'short_url_view',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'short_url_add',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'short_url_edit',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'title' => 'short_url_delete',
                'status' => 'Y',
                'created_at' => now(),
                'updated_at' => now(),
            ],

        ]);
    }


    public function down(): void
    {
        Schema::dropIfExists('tbl_permissions');
    }
};