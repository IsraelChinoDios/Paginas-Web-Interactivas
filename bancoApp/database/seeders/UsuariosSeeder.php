<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Usuario;

class UsuariosSeeder extends Seeder
{
    public function run(): void
    {
        $datos = [
            // Administradores
            [
                'nombre'   => 'Admin Uno',
                'correo'   => 'admin1@bancoapp.local',
                'password' => 'secret123',     // se hashéa por el cast del modelo
                'rfc'      => 'ADUO800101AAA', // 13 chars
                'rol'      => 'administrador',
            ],
            [
                'nombre'   => 'Admin Dos',
                'correo'   => 'admin2@bancoapp.local',
                'password' => 'secret123',
                'rfc'      => 'ADDO810202BBB',
                'rol'      => 'administrador',
            ],

            // Empleados
            [
                'nombre'   => 'Empleado Uno',
                'correo'   => 'empleado1@bancoapp.local',
                'password' => 'empleado123',
                'rfc'      => 'EMUN900303CCC',
                'rol'      => 'empleado',
            ],
            [
                'nombre'   => 'Empleado Dos',
                'correo'   => 'empleado2@bancoapp.local',
                'password' => 'empleado123',
                'rfc'      => 'EMDO920404DDD',
                'rol'      => 'empleado',
            ],
            [
                'nombre'   => 'Empleado Tres',
                'correo'   => 'empleado3@bancoapp.local',
                'password' => 'empleado123',
                'rfc'      => 'EMTR930505EEE',
                'rol'      => 'empleado',
            ],

            // Clientes
            [
                'nombre'   => 'Cliente Uno',
                'correo'   => 'cliente1@bancoapp.local',
                'password' => 'cliente123',
                'rfc'      => 'CLUN000606FFF',
                'rol'      => 'cliente',
            ],
            [
                'nombre'   => 'Cliente Dos',
                'correo'   => 'cliente2@bancoapp.local',
                'password' => 'cliente123',
                'rfc'      => 'CLDO010707GGG',
                'rol'      => 'cliente',
            ],
            [
                'nombre'   => 'Cliente Tres',
                'correo'   => 'cliente3@bancoapp.local',
                'password' => 'cliente123',
                'rfc'      => 'CLTR020808HHH',
                'rol'      => 'cliente',
            ],
            [
                'nombre'   => 'Cliente Cuatro',
                'correo'   => 'cliente4@bancoapp.local',
                'password' => 'cliente123',
                'rfc'      => 'CLCU030909III',
                'rol'      => 'cliente',
            ],
        ];

        foreach ($datos as $d) {
            Usuario::updateOrCreate(
                ['rfc' => $d['rfc']], // evita duplicados si corres el seeder varias veces
                $d
            );
        }
    }
}
