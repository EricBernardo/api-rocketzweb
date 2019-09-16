<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductsAddFieldsNfeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {                        
            $table->string('cfop', 10)->nullable();
            $table->string('ucom', 10)->nullable();
            $table->string('icms', 10)->nullable();
            $table->string('ipi', 10)->nullable();
            $table->string('pis', 10)->nullable();
            $table->string('cofins', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {                                    
            $table->dropColumn('cfop');
            $table->dropColumn('ucom');
            $table->dropColumn('icms');
            $table->dropColumn('ipi');
            $table->dropColumn('pis');
            $table->dropColumn('cofins');
        });
    }
}
