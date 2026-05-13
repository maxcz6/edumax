<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        // Create core roles if Spatie is installed
        if (class_exists(\Spatie\Permission\Models\Role::class)) {
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Administrador']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Director']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Docente']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Estudiante']);
            \Spatie\Permission\Models\Role::firstOrCreate(['name' => 'Padre']);
        }
    }
}
