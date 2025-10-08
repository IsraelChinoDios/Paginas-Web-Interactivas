<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Cuenta;
use App\Models\Pago;
use Illuminate\Support\Str;

class PagosSeeder extends Seeder
{
    public function run(): void
    {
        $servicios = ['luz', 'agua', 'internet', 'telefono', 'streaming', 'gas'];
        $metodos   = ['efectivo', 'tarjeta'];

        Cuenta::all()->each(function ($c) use ($servicios, $metodos) {
            // 3-6 pagos por cuenta
            foreach (range(1, fake()->numberBetween(3, 6)) as $i) {
                Pago::updateOrCreate(
                    ['referencia' => strtoupper(Str::random(12))],
                    [
                        'cuenta_id'    => $c->id,
                        'tipo_servicio'=> fake()->randomElement($servicios),
                        'monto'        => fake()->randomFloat(2, 100, 3000),
                        'metodo_pago'  => fake()->randomElement($metodos),
                        'fecha'        => fake()->dateTimeBetween('-3 months', 'now'),
                    ]
                );
            }
        });
    }
}
