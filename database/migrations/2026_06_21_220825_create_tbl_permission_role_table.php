<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_permission_role', function (Blueprint $table) {

            $table->unsignedInteger('role_id');

            $table->unsignedInteger('permission_id');


            // Indexes
            $table->index('role_id', 'role_id_fk_6744');

            $table->index('permission_id', 'permission_id_fk_6744');


            // Foreign keys
            $table->foreign('permission_id', 'FK_tbl_permission_role_tbl_permissions')
                ->references('id')
                ->on('tbl_permissions')
                ->onDelete('cascade')
                ->onUpdate('restrict');


            $table->foreign('role_id', 'FK_tbl_permission_role_tbl_roles')
                ->references('id')
                ->on('tbl_roles')
                ->onDelete('cascade')
                ->onUpdate('restrict');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tbl_permission_role');
    }
};