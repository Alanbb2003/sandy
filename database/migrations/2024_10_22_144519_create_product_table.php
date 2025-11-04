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
        Schema::create('product', function (Blueprint $table) {
            $table->id();
            $table->string('fotoPromosi');
            $table->string('namaBarang');
            $table->string('slugBarang');
            $table->unsignedBigInteger('fk_kategori');
            $table->string('deskripsi', 1000);
            $table->integer('totalQuantity');
            $table->string('satuanTerkecil', 255);
            $table->integer('isiSatuanBesar')->nullable();
            $table->string('satuanBesar', 255)->nullable();
            $table->integer('hargaKecil');
            $table->integer('hargaBesar')->nullable();
            $table->integer('Status');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product');
    }
};
