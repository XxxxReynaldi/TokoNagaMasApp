<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('purchaseReceiptPath');
            $table->string('bank_account_name');
            $table->string('bank_name');
            $table->string('account_number');
            $table->integer('total_price')->default(0);
            $table->timestamps();
        });

        Schema::create('transaction_details', function (Blueprint $table) {
            $table->foreignId('product_id')->constrained();
            $table->foreignId('product_transaction_id')->constrained();
            $table->integer('quantity')->default(0);
            $table->integer('price')->default(0);
            $table->primary(['product_id', 'product_transaction_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transaction_details');
        Schema::dropIfExists('product_transactions');
    }
}
