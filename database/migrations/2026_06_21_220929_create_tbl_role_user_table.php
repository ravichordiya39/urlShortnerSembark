<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_role_user', function (Blueprint $table) {

            $table->unsignedInteger('user_id');

            $table->unsignedInteger('role_id');


            // Indexes
            $table->index('user_id', 'FK_tbl_role_user_users');

            $table->index('role_id', 'FK_tbl_role_user_tbl_roles');


            // Foreign Keys
            $table->foreign('user_id', 'FK_tbl_role_user_users')
                ->references('id')
                ->on('users')
                ->onDelete('restrict')
                ->onUpdate('restrict');


            $table->foreign('role_id', 'FK_tbl_role_user_tbl_roles')
                ->references('id')
                ->on('tbl_roles')
                ->onDelete('restrict')
                ->onUpdate('restrict');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tbl_role_user');
    }
};