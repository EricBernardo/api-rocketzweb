<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterClientsAddFieldsNfeTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('clients', function (Blueprint $table) {                        
            $table->string('ie')->nullable();
            $table->unsignedTinyInteger('indIEDest')->nullable();
            $table->string('email')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('clients', function (Blueprint $table) {            
            $table->dropColumn('ie');
            $table->dropColumn('indIEDest');            
            $table->dropColumn('email');            
        });
    }
}
