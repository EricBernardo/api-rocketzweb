<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterProductRenameFieldsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function(Blueprint $table) {            
            $table->renameColumn('csosn', 'icms');
            $table->renameColumn('ipi_ipint_cst', 'ipi');
            $table->renameColumn('pis_ipint_cst', 'pis');
            $table->renameColumn('cofins_cofinsnt_cst', 'cofins');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function(Blueprint $table) {
            $table->renameColumn('icms', 'csosn');
            $table->renameColumn('ipi', 'ipi_ipint_cst');
            $table->renameColumn('pis', 'pis_ipint_cst');
            $table->renameColumn('cofins', 'cofins_cofinsnt_cst');
        });
    }
}
