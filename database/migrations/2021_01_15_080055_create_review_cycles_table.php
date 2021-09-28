<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewCyclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('review_cycles', function (Blueprint $table) {
            $table->id();
            $table->string("label")->unique();
            $table->date("start_date");
            $table->date("end_date");
            $table->boolean("is_current")->default(0);
            $table->bigInteger('company_id')->nullable()->default(0);
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
        Schema::dropIfExists('review_cycles');
    }
}
