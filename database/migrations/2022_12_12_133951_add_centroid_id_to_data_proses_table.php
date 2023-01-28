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
        Schema::table('klusters', function (Blueprint $table) {
            $table->foreignId("centroid_id")->nullable();
            $table->foreign("centroid_id")->references("id")->on("centroids")->onDelete("set null");
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('klusters', function (Blueprint $table) {
            $table->dropForeign(["centroid_id"]);
            $table->dropColumn(["centroid_id"]);
        });
    }
};
