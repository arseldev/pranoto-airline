<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('news', function (Blueprint $table) {
            $table->id(); 
            $table->string('title')->unique(); 
            $table->string('slug')->unique(); 
            $table->string('image')->nullable(); 
            $table->text('content')->nullable(); 
            $table->boolean('is_published')->default(false); 
            $table->boolean('is_headline')->default(false); 
            $table->timestamps(); 
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('news');
    }
};
