<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReachedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('reached', function (Blueprint $table) {
            $table->string('uic', 14);
            $table->date('date_reached');
            $table->integer('age');
            $table->string('risk_group');

            $table->string('guid', 25)->default("");
            $table->timestamp('created_on')->nullable();
            $table->integer('counter_last_update')->default(false);

            $table->primary(['uic', 'date_reached']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('reached');
    }
}
