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
        Schema::create('transaksi_details', function (Blueprint $table) {
            $table->id();
            $table->timestamp("tanggal")->default(now())->nullable();
            $table->string("produk");
            $table->string("nomor_struk");
            $table->string("nama_operator");
            $table->integer("metode_pembayaran");
            $table->integer("quantity");
            $table->integer("harga");
            $table->integer("subtotal");
            $table->integer("diskon");
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
        Schema::dropIfExists('transaksi_details');
    }
};
