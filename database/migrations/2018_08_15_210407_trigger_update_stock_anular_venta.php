<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TriggerUpdateStockAnularVenta extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::unprepared('
        CREATE TRIGGER tr_updStockVentaAnular AFTER UPDATE ON ventas
        FOR EACH ROW 
        BEGIN
            UPDATE articulos a
            JOIN detalle_ventas dv
            ON dv.idarticulo = a.id
            AND dv.idventa = new.id
            set a.stock = a.stock + dv.cantidad;
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
        DB::unprepared('DROP TRIGGER `tr_updStockVentaAnular`');

    }
}
