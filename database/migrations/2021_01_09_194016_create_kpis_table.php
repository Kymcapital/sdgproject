<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateKpisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('kpis', function (Blueprint $table) {
            $table->id();
            $table->text("label");
            $table->json("cycle_id")->nullable();
            $table->bigInteger("target")->nullable(0)->default(0);
            $table->bigInteger('company_id')->nullable()->default(0);
            $table->bigInteger('sdg_topic_id')->nullable()->default(0);
            $table->json('division_id')->nullable();
            $table->bigInteger('user_id');
            $table->softDeletes();
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
        Schema::dropIfExists('kpis');
    }
}
