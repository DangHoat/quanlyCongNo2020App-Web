<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('clients', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code',191)->unique();
            $table->string('name');
            $table->string('address')->nullable();
            $table->string('telephone')->nullable();
            $table->string('worker')->nullable();
            $table->string('status');  //pending, resolved
            $table->text('note')->nullable();
            $table->date('date_limit')->nullable();
            $table->decimal('money_limit',15,0)->nullable();
            $table->integer('number_update_bills')->default(0);
            /**
            *$table->double('money_debt');
            */
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
        Schema::dropIfExists('clients');
    }
}
