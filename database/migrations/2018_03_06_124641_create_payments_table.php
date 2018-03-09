<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->increments('ID');
			$table->decimal('UnitPrice', 13,2)->default('0');
			$table->timestamp('Date')->default(now());
			$table->integer('Store')->nullable();
			$table->integer('PaymentType')->default('1');
			$table->integer('Bank')->nullable();
			$table->integer('Instalment')->nullable();
			$table->string('TrxCode')->default('')->unique();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
