<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeDatatypePhotoUrl extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            $table->text('productPhotoPath')->change();
        });
        Schema::table('galleries', function (Blueprint $table) {
            $table->text('galleryPhotoPath')->change();
        });
        Schema::table('mechanics', function (Blueprint $table) {
            $table->text('mechanicPhotoPath')->change();
        });
        Schema::table('mechanic_transactions', function (Blueprint $table) {
            $table->text('purchaseReceiptPath')->change();
        });
        Schema::table('product_transactions', function (Blueprint $table) {
            $table->text('purchaseReceiptPath')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
