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
        Schema::table('comments', function (Blueprint $table) {
            // First add the column without the foreign key constraint
            $table->unsignedBigInteger('parent_id')->nullable();
            
            // Then add the foreign key constraint
            $table->foreign('parent_id')
                  ->references('id')
                  ->on('comments')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('comments', function (Blueprint $table) {
            // First drop the foreign key constraint
            $table->dropForeign(['parent_id']);
            // Then drop the column
            $table->dropColumn('parent_id');
        });
    }
};
