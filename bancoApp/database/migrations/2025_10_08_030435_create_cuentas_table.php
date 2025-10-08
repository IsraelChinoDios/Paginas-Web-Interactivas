<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cuentas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('usuario_id')
                  ->constrained('usuarios')
                  ->cascadeOnDelete();
            $table->enum('tipo', ['nomina', 'credito'])->index();
            $table->decimal('saldo', 12, 2)->default(0);
            $table->timestamps();

            $table->index(['usuario_id', 'tipo']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cuentas');
    }
};
