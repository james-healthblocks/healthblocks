<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateServiceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('service', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shc_id')->unsigned();
            $table->date('sdate');
            $table->tinyInteger('service_type');
            $table->tinyInteger('client_type');
            $table->string('venue', 100)->nullable();
            $table->tinyInteger('sex');
            $table->integer('count')->default(0);

            $table->boolean('invalid')->default(0);
            $table->string('guid', 25);
            // $table->timestamp('created_on');
            $table->integer('counter_last_update');

            $table->timestamps();

            $table->unique(['shc_id', 'sdate', 'service_type', 'client_type', 'venue', 'sex']);
            // $table->foreign('shc_id')->references('shc_id')->on('shclinic');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('service');
    }
}
