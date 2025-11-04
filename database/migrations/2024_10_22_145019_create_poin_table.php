<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('poin', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('memberID');
            $table->unsignedBigInteger('htransID')->nullable();
            $table->dateTime('tanggalPemberianPoin');
            $table->integer('jumlahPoin');
            $table->string('tipeTransaksi', 255);
            $table->string('sumberPoin', 255);
            $table->dateTime('tanggalKadaluwarsaPoin')->nullable();
            $table->integer('saldoPoin');
            $table->timestamps();

            // Add index for memberID
            $table->index('memberID');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('poin');
    }
};
