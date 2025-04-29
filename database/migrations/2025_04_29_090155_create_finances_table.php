<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('finances', function (Blueprint $table) {
            $table->id();

            $table->date('date'); 
            $table->enum('flow_type', ['in', 'budget']); 
            $table->unsignedInteger('amount'); 
            $table->text('note')->nullable(); 
        
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('finances');
    }
};
