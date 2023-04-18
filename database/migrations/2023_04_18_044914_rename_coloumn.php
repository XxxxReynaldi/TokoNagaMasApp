<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColoumn extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->renameColumn('phoneNumber', 'phone_number');
            $table->renameColumn('profile_photo_path', 'profilePhotoPath');
        });
        Schema::table('galleries', function (Blueprint $table) {
            $table->renameColumn('RepairType', 'repair_type');
        });
        Schema::table('mechanic_transactions', function (Blueprint $table) {
            $table->renameColumn('purchase_receipt_path', 'purchaseReceiptPath');
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
