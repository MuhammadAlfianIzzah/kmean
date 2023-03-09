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
        Schema::create('klusters', function (Blueprint $table) {
            $table->id();
            $table->foreignId("transaksi_detail_id")->nullable();
            $table->foreign("transaksi_detail_id")->references("id")->on("transaksi_details")->onDelete("set null");
            $table->foreignId("data_proses_id")->nullable();
            $table->foreign("data_proses_id")->references("id")->on("data_proses")->onDelete("set null");
            $table->string("nama");
            $table->float("c1")->nullable();
            $table->float("c2")->nullable();
            $table->float("c3")->nullable();
            $table->float("c4")->nullable();
            $table->float("c5")->nullable();
            $table->float("c6")->nullable();
            $table->string("c_min")->nullable();
            $table->float("nilai_cmin")->nullable();
            $table->string("literasi")->nullable();
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
        Schema::dropIfExists('klusters');
    }
};
