<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('parkings', function (Blueprint $table) {


            if (Schema::hasColumn('parkings', 'price_car')) {
                $table->dropColumn('price_car');
            }

            if (Schema::hasColumn('parkings', 'price_motorcycle')) {
                $table->dropColumn('price_motorcycle');
            }

        
            $table->decimal('price', 8, 2)->after('opening_hours');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
