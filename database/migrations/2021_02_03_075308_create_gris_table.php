<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGrisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('gris', function (Blueprint $table) {
            $table->id();
            $table->integer('gri_number')->nullable()->default(0);
            $table->text("description")->nullable();
            $table->bigInteger('company_id')->nullable()->default(0);
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
        Schema::dropIfExists('gris');
    }
}
