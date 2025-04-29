<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('budget_expenses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('finance_id');
            $table->string('description');
            $table->integer('amount');
            $table->timestamps();

            $table->foreign('finance_id')
                  ->references('id')
                  ->on('finances')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('budget_expenses');
    }
};
