<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoansTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loans', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->bigInteger('amount');
            $table->integer('duration');
            $table->enum('repayment_frequency', ['weekly', 'monthly']);
            $table->decimal('interest_rate', 8, 2);
            $table->bigInteger('arrangement_fee');
            $table->bigInteger('fix_installment');
            $table->boolean('is_settled')->default(0);
            $table->timestamps();
            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('loans');
    }
}
