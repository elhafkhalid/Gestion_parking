<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void {
        Schema::create('parking_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')
                ->constrained();

            $table->foreignId('place_id')
                ->constrained();

            $table->foreignId('agent_id')
                ->constrained('users');

            $table->dateTime('entry_time');
            $table->dateTime('exit_time')->nullable();
            $table->decimal('total_price', 8, 2)->nullable();
            $table->timestamps();
        });
    }
     
    public function down(): void
    {
        Schema::dropIfExists('parking_records');
    }
};
