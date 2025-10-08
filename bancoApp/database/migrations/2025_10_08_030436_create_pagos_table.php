<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pagos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cuenta_id')
                  ->constrained('cuentas')
                  ->cascadeOnDelete();
            $table->string('referencia')->unique();
            $table->string('tipo_servicio'); // luz, agua, internet, etc.
            $table->decimal('monto', 12, 2);
            $table->enum('metodo_pago', ['efectivo', 'tarjeta'])->index();
            $table->dateTime('fecha');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pagos');
    }
};
