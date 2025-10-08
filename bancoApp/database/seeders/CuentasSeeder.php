<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;
use App\Models\Cuenta;

class CuentasSeeder extends Seeder
{
    public function run(): void
    {
        // para cada usuario crea 1-2 cuentas
        Usuario::all()->each(function ($u) {
            $tipos = collect(['nomina', 'credito']);

            // Siempre una de nómina
            Cuenta::updateOrCreate(
                ['usuario_id' => $u->id, 'tipo' => 'nomina'],
                ['saldo' => fake()->randomFloat(2, 1000, 50000)]
            );

            // Aleatoriamente una de crédito
            if (fake()->boolean(60)) {
                Cuenta::updateOrCreate(
                    ['usuario_id' => $u->id, 'tipo' => 'credito'],
                    ['saldo' => fake()->randomFloat(2, -20000, 2000)] // saldo puede ser negativo (adeudo)
                );
            }
        });
    }
}
