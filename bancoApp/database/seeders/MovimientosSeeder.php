<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuenta;
use App\Models\Movimiento;

class MovimientosSeeder extends Seeder
{
    public function run(): void
    {
        $tipos = ['deposito', 'retiro', 'transferencia', 'ajuste'];

        Cuenta::all()->each(function ($c) use ($tipos) {
            // 5-10 movimientos por cuenta
            foreach (range(1, fake()->numberBetween(5, 10)) as $i) {
                $tipo  = fake()->randomElement($tipos);
                $monto = fake()->randomFloat(2, 50, 10000);

                // depósitos suman, retiros/transferencias restan, ajuste aleatorio
                if (in_array($tipo, ['retiro', 'transferencia']) && $c->tipo !== 'credito') {
                    $monto = -$monto;
                }

                Movimiento::create([
                    'cuenta_id'       => $c->id,
                    'tipo_movimiento' => $tipo,
                    'monto'           => $monto,
                    'fecha'           => fake()->dateTimeBetween('-3 months', 'now'),
                ]);
            }
        });
    }
}
