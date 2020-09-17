<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBillsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('bills', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('idClient')->unsigned();
            $table->foreign('idClient')->references('id')->on('clients');
            $table->date('date');
            $table->string('construction_address')->nullable();
            $table->string('categories');
            $table->string('types')->nullable();
            $table->string('unit')->nullable();
            $table->float('quantity',12,3)->nullable();
            $table->decimal('unit_price',15,0)->nullable();
            $table->decimal('total_amount',15,0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('bills');
    }
}
