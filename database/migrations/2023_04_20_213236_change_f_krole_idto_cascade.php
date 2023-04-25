<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeFKroleIdtoCascade extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // hapus foreign key yang baru
            $table->dropForeign(['role_id']);

            // tambahkan foreign key lama dengan ON DELETE RESTRICT
            $table->foreign('role_id')->references('id')->on('roles')->onDelete('Cascade');
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
