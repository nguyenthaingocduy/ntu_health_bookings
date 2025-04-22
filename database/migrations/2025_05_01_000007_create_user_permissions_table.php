<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('user_permissions', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('user_id');
            $table->uuid('permission_id');
            $table->boolean('can_view')->default(false);
            $table->boolean('can_create')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->boolean('can_delete')->default(false);
            $table->uuid('granted_by');
            $table->timestamps();
            
            $table->foreign('user_id')->references('id')->on('users')->cascadeOnDelete();
            $table->foreign('permission_id')->references('id')->on('permissions')->cascadeOnDelete();
            $table->foreign('granted_by')->references('id')->on('users');
            
            $table->unique(['user_id', 'permission_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_permissions');
    }
};
