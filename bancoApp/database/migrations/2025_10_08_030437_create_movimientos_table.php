<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('movimientos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_id')
                  ->constrained('cuentas')
                  ->cascadeOnDelete();
            $table->string('tipo_movimiento')->index(); // depósito, retiro, transferencia, ajuste...
            $table->decimal('monto', 12, 2);
            $table->dateTime('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movimientos');
    }
};
