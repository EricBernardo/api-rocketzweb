<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrderAddFieldsIdeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->integer('finNFe')->nullable(0);
            $table->integer('tpNF')->default(0);
            $table->integer('idDest')->default(0);
            $table->integer('tpImp')->default(0);
            $table->integer('tpEmis')->default(0);
            $table->integer('indFinal')->default(0);
            $table->integer('indPres')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('finNFe');
            $table->dropColumn('tpNF');
            $table->dropColumn('idDest');
            $table->dropColumn('tpImp');
            $table->dropColumn('tpEmis');
            $table->dropColumn('indFinal');
            $table->dropColumn('indPres');
        });
    }
}
