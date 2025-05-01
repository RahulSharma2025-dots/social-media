<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->enum('user_type', ['influencer', 'normal'])->default('normal');
            $table->string('category')->nullable();
            $table->decimal('wallet_balance', 10, 2)->default(0);
            $table->string('profile_picture')->nullable();
            $table->text('bio')->nullable();
            $table->string('username')->unique();
            $table->boolean('is_admin')->default(false);
            $table->boolean('is_verified')->default(false);
            $table->boolean('is_banned')->default(false);
            $table->string('verification_status')->nullable();
            $table->integer('reports_count')->default(0);
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('users');
    }
}; 