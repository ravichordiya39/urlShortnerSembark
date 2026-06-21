<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tbl_short_urls', function (Blueprint $table) {

            $table->increments('id');

            $table->longText('original_url')
                ->nullable();

            $table->longText('short_url')
                ->nullable();


            $table->unsignedInteger('created_by')
                ->nullable();

            $table->unsignedInteger('company_id')
                ->nullable();


            $table->enum('status',['Y','N'])
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


            // Indexes
            $table->index('created_by');
            $table->index('company_id');

        });
    }


    public function down(): void
    {
        Schema::dropIfExists('tbl_short_urls');
    }
};