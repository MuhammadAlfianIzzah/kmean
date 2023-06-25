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
        Schema::create('history_engines', function (Blueprint $table) {
            $table->id();
            $table->string("kode");
            $table->string("nama_barang");
            $table->integer("stok_awal");
            $table->integer("ttl_penjualan");
            $table->integer("stok_akhir");
            $table->integer("cluster")->nullable();
            $table->foreignUuid('engine_id')->references("id")->on("engines")->onDelete("cascade");
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
        Schema::dropIfExists('history_engines');
    }
};
