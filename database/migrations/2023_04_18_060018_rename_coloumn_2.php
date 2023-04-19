<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameColoumn2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->renameColumn('desc', 'description');
        });
        Schema::table('mechanics', function (Blueprint $table) {
            $table->renameColumn('desc', 'description');
        });
        Schema::table('products', function (Blueprint $table) {
            $table->renameColumn('desc', 'description');
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
