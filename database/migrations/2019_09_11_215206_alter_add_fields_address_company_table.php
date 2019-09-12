<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterAddFieldsAddressCompanyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {                        
            $table->string('fantasy')->nullable();
            $table->string('ie')->nullable();
            $table->unsignedTinyInteger('crt')->nullable();
            $table->string('cnpj', 14)->nullable();
            $table->string('address')->nullable();
            $table->integer('number')->nullable();
            $table->string('neighborhood')->nullable();            
            $table->unsignedInteger('state_id')->nullable();
            $table->foreign('state_id')->references('id')->on('states')->onDelete('cascade');
            $table->unsignedInteger('city_id')->nullable();
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->string('cep', 9)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('companies', function (Blueprint $table) {
            $table->dropColumn('fantasy');
            $table->dropColumn('ie');
            $table->dropColumn('crt');
            $table->dropColumn('cnpj');
            $table->dropColumn('address');
            $table->dropColumn('number');
            $table->dropColumn('neighborhood');
            $table->dropColumn('state_id');
            $table->dropColumn('city_id');            
            $table->dropColumn('cep');            
        });
    }
}
