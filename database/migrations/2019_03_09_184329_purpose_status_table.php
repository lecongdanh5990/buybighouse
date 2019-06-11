<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PurposeStatusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purpose_status', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('purpose_id')->unsigned();
            $table->integer('status_id')->unsigned();

            $table->foreign('purpose_id')->references('id')->on('purposes')->onDelete('cascade');;
            $table->foreign('status_id')->references('id')->on('statuses')->onDelete('cascade');;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('purpose_status');
    }
}
