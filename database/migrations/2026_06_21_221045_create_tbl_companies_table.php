<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_companies', function (Blueprint $table) {

            $table->increments('id');

            $table->longText('title')
                ->nullable();

            $table->enum('status', ['Y','N'])
                ->default('Y')
                ->nullable();

            $table->timestamp('created_at')
                ->useCurrent()
                ->nullable();

            $table->timestamp('updated_at')
                ->useCurrent()
                ->nullable();

            $table->timestamp('deleted_at')
                ->nullable();

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tbl_companies');
    }
};