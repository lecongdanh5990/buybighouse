<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTargetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('targets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('purpose_id')->unsigned();
            $table->boolean('complete');
            $table->boolean('repeat');
            $table->string('frequency')->nullable();
            $table->integer('time_to_complete');
            $table->integer('time_has_completed');
            $table->boolean('ontime')->nullable();
            $table->date('last_completed_day')->nullable();
            $table->date('last_date_to_do')->nullable();
            
            $table->timestamps();
            

            $table->foreign('purpose_id')->references('id')->on('purposes')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('targets');
    }
}
