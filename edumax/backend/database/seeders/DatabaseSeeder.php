<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\Institucion;
use App\Models\User;
use App\Models\Docente;
use App\Models\Padre;
use App\Models\Estudiante;
use App\Models\Grado;
use App\Models\Seccion;
use App\Models\Curso;
use App\Models\Matricula;
use App\Models\Asistencia;
use App\Models\Tarea;
use App\Models\Nota;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Crear roles
        $adminRole = Role::firstOrCreate(
            ['nombre' => 'Administrador'],
            ['descripcion' => 'Administrador del sistema']
        );
        $directorRole = Role::firstOrCreate(
            ['nombre' => 'Director'],
            ['descripcion' => 'Director de la institución']
        );
        $docenteRole = Role::firstOrCreate(
            ['nombre' => 'Docente'],
            ['descripcion' => 'Docente/Profesor']
        );
        $estudianteRole = Role::firstOrCreate(
            ['nombre' => 'Estudiante'],
            ['descripcion' => 'Estudiante de la institución']
        );
        $padreRole = Role::firstOrCreate(
            ['nombre' => 'Padre'],
            ['descripcion' => 'Padre de familia']
        );

        // Crear instituciones de demostración
        $institucion1 = Institucion::firstOrCreate(
            ['codigo_modular' => '061234'],
            [
                'nombre' => 'Instituto Educativo EduMax',
                'direccion' => 'Av. Principal 123, Lima',
                'telefono' => '(01) 1234567',
                'correo' => 'info@edumax.edu.pe',
                'estado' => 'activo',
            ]
        );

        // Crear usuario administrador
        $adminUser = User::firstOrCreate(
            ['email' => 'admin@edumax.edu.pe'],
            [
                'institucion_id' => $institucion1->id,
                'role_id' => $adminRole->id,
                'nombres' => 'Admin',
                'apellidos' => 'Sistema',
                'password' => bcrypt('password'),
                'telefono' => '9876543210',
                'estado' => 'activo',
            ]
        );

        // Crear director
        $directorUser = User::firstOrCreate(
            ['email' => 'director@edumax.edu.pe'],
            [
                'institucion_id' => $institucion1->id,
                'role_id' => $directorRole->id,
                'nombres' => 'Carlos',
                'apellidos' => 'García',
                'password' => bcrypt('password'),
                'telefono' => '9876543211',
                'estado' => 'activo',
            ]
        );

        // Crear docentes
        $docentes = [];
        for ($i = 0; $i < 3; $i++) {
            $docenteUser = User::firstOrCreate(
                ['email' => "docente{$i}@edumax.edu.pe"],
                [
                    'institucion_id' => $institucion1->id,
                    'role_id' => $docenteRole->id,
                    'nombres' => "Profesor {$i}",
                    'apellidos' => "Pérez García",
                    'password' => bcrypt('password'),
                    'telefono' => "987654321{$i}",
                    'estado' => 'activo',
                ]
            );
            
            Docente::firstOrCreate(
                ['user_id' => $docenteUser->id],
                [
                    'especialidad' => 'Educación',
                    'grado_academico' => 'Licenciatura',
                    'fecha_contratacion' => now()->subYears(2),
                ]
            );
            
            $docentes[] = $docenteUser;
        }

        // Crear padres
        $padres = [];
        for ($i = 0; $i < 5; $i++) {
            $padreUser = User::firstOrCreate(
                ['email' => "padre{$i}@example.com"],
                [
                    'institucion_id' => $institucion1->id,
                    'role_id' => $padreRole->id,
                    'nombres' => "Padre {$i}",
                    'apellidos' => "Responsable",
                    'password' => bcrypt('password'),
                    'telefono' => "998765432{$i}",
                    'estado' => 'activo',
                ]
            );
            
            $padre = Padre::firstOrCreate(
                ['user_id' => $padreUser->id],
                [
                    'ocupacion' => 'Profesional',
                    'parentesco' => 'Padre',
                ]
            );
            
            $padres[] = $padre;
        }

        // Crear grados
        $grados = [];
        foreach (['1°', '2°', '3°'] as $nombre) {
            $grado = Grado::firstOrCreate(
                ['institucion_id' => $institucion1->id, 'nombre' => $nombre],
                [
                    'nivel' => 'Secundaria',
                ]
            );
            $grados[] = $grado;
        }

        // Crear secciones
        $secciones = [];
        foreach ($grados as $grado) {
            foreach (['A', 'B'] as $seccionNombre) {
                $seccion = Seccion::firstOrCreate(
                    ['grado_id' => $grado->id, 'nombre' => $seccionNombre],
                    [
                        'aula' => "{$grado->nombre}-{$seccionNombre}",
                    ]
                );
                $secciones[] = $seccion;
            }
        }

        // Crear cursos
        $cursos = [];
        $nombresCursos = ['Matemáticas', 'Lenguaje', 'Ciencias', 'Historia', 'Inglés'];
        foreach ($nombresCursos as $nombreCurso) {
            foreach ($grados as $grado) {
                $docente = $docentes[array_rand($docentes)];
                $curso = Curso::firstOrCreate(
                    ['institucion_id' => $institucion1->id, 'docente_id' => $docente->docente->id, 'grado_id' => $grado->id, 'nombre' => $nombreCurso],
                    [
                        'descripcion' => "Curso de {$nombreCurso}",
                        'estado' => 'activo',
                    ]
                );
                $cursos[] = $curso;
            }
        }

        // Crear estudiantes y matrículas
        for ($i = 0; $i < 20; $i++) {
            $padreAsignado = $padres[$i % count($padres)];
            
            $estudianteUser = User::factory()->create([
                'institucion_id' => $institucion1->id,
                'role_id' => $estudianteRole->id,
                'nombres' => "Estudiante {$i}",
                'apellidos' => "Apellido {$i}",
                'email' => "estudiante{$i}@edumax.edu.pe",
                'estado' => 'activo',
            ]);
            
            $estudiante = Estudiante::firstOrCreate(
                ['user_id' => $estudianteUser->id],
                [
                    'padre_id' => $padreAsignado->id,
                    'codigo_estudiante' => "EST{$i:06d}",
                    'dni' => "1234567{$i:02d}",
                    'fecha_nacimiento' => now()->subYears(15),
                    'genero' => $i % 2 == 0 ? 'M' : 'F',
                    'direccion' => "Calle {$i}, Lima",
                    'estado' => 'activo',
                ]
            );

            // Crear matrículas para cursos del grado 1
            $seccionesGrado1 = Seccion::whereHas('grado', function ($q) use ($grados) {
                $q->where('id', $grados[0]->id);
            })->get();

            foreach ($seccionesGrado1 as $seccion) {
                $cursosSeccion = Curso::where('grado_id', $seccion->grado_id)->limit(3)->get();
                foreach ($cursosSeccion as $curso) {
                    Matricula::firstOrCreate(
                        ['estudiante_id' => $estudiante->id, 'curso_id' => $curso->id],
                        [
                            'seccion_id' => $seccion->id,
                            'anio_escolar' => date('Y'),
                            'fecha_matricula' => now(),
                            'estado' => 'activo',
                        ]
                    );
                }
            }
        }

        // Crear tareas
        $cursosMuestra = Curso::limit(3)->get();
        foreach ($cursosMuestra as $curso) {
            for ($j = 0; $j < 2; $j++) {
                Tarea::firstOrCreate(
                    ['curso_id' => $curso->id, 'titulo' => "Tarea {$j} - {$curso->nombre}"],
                    [
                        'descripcion' => "Descripción de la tarea",
                        'fecha_publicacion' => now(),
                        'fecha_entrega' => now()->addDays(7),
                        'estado' => 'activo',
                    ]
                );
            }
        }

        // Crear asistencias
        $estudiantesMuestra = Estudiante::limit(5)->get();
        foreach ($estudiantesMuestra as $estudiante) {
            $matriculas = $estudiante->matriculas()->limit(2)->get();
            foreach ($matriculas as $matricula) {
                for ($d = 0; $d < 5; $d++) {
                    Asistencia::firstOrCreate(
                        ['estudiante_id' => $estudiante->id, 'curso_id' => $matricula->curso_id, 'fecha' => now()->subDays($d)],
                        [
                            'estado' => ['presente', 'tardanza', 'falta'][array_rand(['presente', 'tardanza', 'falta'])],
                        ]
                    );
                }
            }
        }

        // Crear notas
        foreach ($estudiantesMuestra as $estudiante) {
            $matriculas = $estudiante->matriculas()->limit(2)->get();
            foreach ($matriculas as $matricula) {
                Nota::firstOrCreate(
                    ['estudiante_id' => $estudiante->id, 'curso_id' => $matricula->curso_id],
                    [
                        'nota' => rand(12, 20),
                        'observacion' => 'Buen desempeño',
                        'fecha_registro' => now(),
                    ]
                );
            }
        }

        $this->command->info('✓ Base de datos poblada exitosamente');
    }
}

