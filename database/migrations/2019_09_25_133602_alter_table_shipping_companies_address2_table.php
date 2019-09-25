<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableShippingCompaniesAddress2Table extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('shipping_companies', function (Blueprint $table) {
            $table->string('cep', 9)->nullable();
            $table->char('cnpj', 14);
            $table->string('fantasy')->nullable();
            $table->integer('number')->nullable();
            $table->string('neighborhood')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shipping_companies', function (Blueprint $table) {
            $table->dropColumn('cep');
            $table->dropColumn('cnpj');
            $table->dropColumn('fantasy');
            $table->dropColumn('number');
            $table->dropColumn('neighborhood');
        });
    }
}
