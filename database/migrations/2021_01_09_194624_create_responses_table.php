<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResponsesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('responses', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('kpi_id');
            $table->bigInteger("target")->nullable(0)->default(0);
            $table->bigInteger("achievement")->nullable(0)->default(0);
            $table->bigInteger("sub_total")->nullable(0)->default(0);
            $table->bigInteger("total")->nullable(0)->default(0);
            $table->bigInteger('company_id')->nullable()->default(0);
            $table->bigInteger('sdg_topic_id')->nullable()->default(0);
            $table->json('division_id');
            $table->bigInteger('user_id')->nullable()->default(0);
            $table->bigInteger('status')->nullable()->default(1);
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
        Schema::dropIfExists('responses');
    }
}
