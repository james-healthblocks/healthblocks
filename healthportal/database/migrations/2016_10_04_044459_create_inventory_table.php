<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInventoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inventory', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('shc_id')->unsigned();
            $table->integer('month');
            $table->integer('year');
            $table->string('item_name', 100);
            $table->date('expiry_date');

            $table->boolean('invalid')->default(0);
            $table->string('guid', 25);
            // $table->timestamp('created_on');
            $table->integer('counter_last_update');

            $table->timestamps();

            $table->unique(['shc_id', 'month', 'year', 'item_name', 'expiry_date']);
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
        Schema::dropIfExists('inventory');
    }
}
