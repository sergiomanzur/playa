<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('pagos', function (Blueprint $table) {
            $table->decimal('cantidad',9,2)->nullable()->change();
        });

        Schema::table('promesas', function (Blueprint $table) {
            $table->decimal('cantidad',9,2)->nullable()->change();
        });

        Schema::table('balances', function (Blueprint $table) {
            //
            $table->decimal('total',9,2)->nullable()->change();
            $table->decimal('credito',9,2)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
};
