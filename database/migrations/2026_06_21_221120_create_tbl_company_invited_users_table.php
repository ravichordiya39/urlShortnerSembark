<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_company_invited_users', function (Blueprint $table) {

            $table->increments('id');

            $table->unsignedInteger('company_id')
                ->nullable();

            $table->unsignedInteger('user_id')
                ->nullable();

            $table->unsignedInteger('invited_by')
                ->nullable();


            $table->timestamp('created_at')
                ->useCurrent()
                ->nullable();

            $table->timestamp('updated_at')
                ->useCurrent()
                ->nullable();

            $table->timestamp('deleted_at')
                ->nullable();


            // Optional indexes
            $table->index('company_id');
            $table->index('user_id');
            $table->index('invited_by');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tbl_company_invited_users');
    }
};