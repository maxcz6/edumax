<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Institucion;

class InstitucionSeeder extends Seeder
{
    public function run(): void
    {
        Institucion::firstOrCreate([
            'codigo' => 'DEFAULT',
        ], [
            'nombre' => 'Institución por defecto',
            'direccion' => null,
            'telefono' => null,
            'email' => null,
            'meta' => null,
        ]);
    }
}
