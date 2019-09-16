<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterOrdersAddShippingVehiclesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('orders', function (Blueprint $table) {                        
            $table->unsignedInteger('shipping_company_id')->nullable();
            $table->foreign('shipping_company_id')->references('id')->on('shipping_companies')->onDelete('cascade');
            $table->unsignedInteger('shipping_company_vehicle_id')->nullable();
            $table->foreign('shipping_company_vehicle_id')->references('id')->on('shipping_company_vehicles')->onDelete('cascade');            
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
            $table->dropColumn('shipping_company_id');
            $table->dropColumn('shipping_company_vehicle_id');
        });
    }
}
