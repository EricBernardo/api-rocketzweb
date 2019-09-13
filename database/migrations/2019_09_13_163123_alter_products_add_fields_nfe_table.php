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
            $table->integer('cfop')->nullable();
            $table->string('ucom', 10)->nullable();
            $table->integer('csosn')->nullable();
            $table->integer('ipi_ipint_cst')->nullable();
            $table->integer('pis_ipint_cst')->nullable();
            $table->integer('cofins_cofinsnt_cst')->nullable();
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
            $table->dropColumn('ucom', 10)->nullable();
            $table->dropColumn('csosn');
            $table->dropColumn('ipi_ipint_cst');
            $table->dropColumn('pis_ipint_cst');
            $table->dropColumn('cofins_cofinsnt_cst');
        });
    }
}
