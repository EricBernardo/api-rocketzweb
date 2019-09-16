<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterCompanyAddFieldsTaxTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('companies', function (Blueprint $table) {                                    
            $table->double('irpj', 3, 1)->default(0);
            $table->double('cofins', 3, 1)->default(0);
            $table->double('pis', 3, 1)->default(0);
            $table->double('csll', 3, 1)->default(0);
            $table->double('iss', 3, 1)->default(0);
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
            $table->dropColumn('irpj');
            $table->dropColumn('cofins');
            $table->dropColumn('pis');
            $table->dropColumn('csll');
            $table->dropColumn('iss');
        });
    }
}
