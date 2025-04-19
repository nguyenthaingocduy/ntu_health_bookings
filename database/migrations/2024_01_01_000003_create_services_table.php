<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // This migration is skipped as the services table already exists
        // Schema::create('services', function (Blueprint $table) {
        //     $table->uuid('id')->primary();
        //     $table->string('name');
        //     $table->text('description');
        //     $table->decimal('price', 10, 2);
        //     $table->string('duration')->default('30'); // in minutes
        //     $table->string('image_url')->nullable();
        //     $table->boolean('is_active')->default(true);
        //     $table->timestamps();
        // });
    }

    public function down()
    {
        // Schema::dropIfExists('services');
    }
};