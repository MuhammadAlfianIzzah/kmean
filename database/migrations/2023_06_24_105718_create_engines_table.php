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
        Schema::create('engines', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string("nama");
            $table->integer("jumlah_cluster")->nullable();
            $table->integer("max_literasi")->nullable();
            $table->string("job_id")->nullable();
            $table->string("elbow")->nullable();
            $table->string("legent")->nullable();
            $table->foreignId("user_id")->nullable();
            $table->foreign("user_id")->references("id")->on("users")->onDelete("cascade");
            $table->timestamp("finish_at")->nullable();
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
        Schema::dropIfExists('engines');
    }
};
