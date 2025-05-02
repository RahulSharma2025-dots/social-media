<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['deposit', 'withdrawal', 'purchase', 'earning']);
            $table->decimal('amount', 10, 2);
            $table->string('transaction_id')->unique();
            $table->enum('status', ['pending', 'completed', 'failed'])->default('pending');
            $table->text('description')->nullable();
            
            // For polymorphic relationship with shorter index name
            $table->string('transactionable_type');
            $table->unsignedBigInteger('transactionable_id');
            $table->index(['transactionable_type', 'transactionable_id'], 'trans_type_id_index');
            
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('wallet_transactions');
    }
}; 