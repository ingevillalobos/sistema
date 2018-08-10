<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerUpdateStockAnularIngreso extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER tr_updStockIngresoAnular AFTER UPDATE ON ingresos
        FOR EACH ROW 
        BEGIN
            UPDATE articulos a
            JOIN detalle_ingresos di
            ON di.idarticulo = a.id
            AND di.idingreso = new.id
            set a.stock = a.stock - di.cantidad;
         END   
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER `tr_updStockIngresoAnular`');

    }
}
