<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_update_sensor_data_table.php
    public function up()
    {
        Schema::table('sensor_data', function (Blueprint $table) {
            if (!Schema::hasColumn('sensor_data', 'gas_value_mq4')) {
                $table->integer('gas_value_mq4')->nullable();
            }
            if (!Schema::hasColumn('sensor_data', 'gas_value_mq6')) {
                $table->integer('gas_value_mq6')->nullable();
            }
            if (!Schema::hasColumn('sensor_data', 'gas_value_mq8')) {
                $table->integer('gas_value_mq8')->nullable();
            }
        });

        // Hapus kolom lama gas_value jika ada
        Schema::table('sensor_data', function (Blueprint $table) {
            if (Schema::hasColumn('sensor_data', 'gas_value')) {
                $table->dropColumn('gas_value');
            }
        });
    }

    public function down()
    {
        // Menambah kembali kolom yang dihapus jika rollback
        Schema::table('sensor_data', function (Blueprint $table) {
            $table->integer('gas_value')->nullable();
            $table->dropColumn(['gas_value_mq4', 'gas_value_mq6', 'gas_value_mq8']);
        });
    }



};
