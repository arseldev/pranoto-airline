<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('public_informations', function (Blueprint $table) {
            $table->id();

            // File Upload
            $table->string('ktp');
            $table->string('surat_pertanggungjawaban');
            $table->text('surat_permintaan');

            // Data Pribadi
            $table->string('nama');
            $table->text('alamat');
            $table->string('pekerjaan');
            $table->string('npwp');
            $table->string('no_hp');
            $table->string('email');

            // Permintaan Informasi
            $table->text('rincian_informasi');
            $table->text('tujuan_informasi');
            $table->string('cara_memperoleh');
            $table->string('cara_salinan');

            $table->timestamps(); // created_at & updated_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('public_informations');
    }
};
