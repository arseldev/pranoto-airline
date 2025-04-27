<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('ads', function (Blueprint $table) {
            $table->id();
            $table->string('ad_name');
            $table->text('description');
            $table->string('ad_type');
            $table->string('documents'); 
            $table->enum('submission_status', ['diajukan', 'disetujui', 'ditolak'])->default('diajukan');
            $table->timestamps();
        });
    }
    public function down(): void
    {
        Schema::dropIfExists('ads');
    }
};
