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
        Schema::create('centroids', function (Blueprint $table) {
            $table->id();
            $table->foreignId("data_proses_id")->nullable();
            $table->foreign("data_proses_id")->references("id")->on("data_proses")->onDelete("set null");
            $table->string("nama");
            $table->float("c1")->nullable();
            $table->float("c2")->nullable();
            $table->float("c3")->nullable();
            // $table->float("c4")->nullable();
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
        Schema::dropIfExists('centroids');
    }
};
