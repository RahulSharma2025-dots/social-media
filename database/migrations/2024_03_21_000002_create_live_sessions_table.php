<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('live_sessions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('title', 255);
            $table->text('description');
            $table->dateTime('scheduled_at');
            $table->integer('duration_minutes');
            $table->decimal('price', 10, 2)->default(0.00);
            $table->string('stream_url')->nullable();
            $table->string('stream_key', 64)->unique();
            $table->enum('status', ['scheduled', 'live', 'ended', 'cancelled'])->default('scheduled');
            $table->boolean('is_live')->default(false);
            $table->integer('viewers_count')->default(0);
            $table->dateTime('ended_at')->nullable();
            $table->timestamps();

            // Add indexes for frequently queried columns
            $table->index('scheduled_at');
            $table->index('is_live');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('live_sessions');
    }
}; 