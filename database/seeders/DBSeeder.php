<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Role;
use App\Models\Cita;
use App\Models\Holiday;
use App\Models\Schedule;
use Illuminate\Support\Facades\Hash;

class DBSeeder extends Seeder
{
    public function run(): void
    {
        // 1️⃣ Crear roles
        $roles = ['admin', 'director', 'profesor', 'user', 'psicologo'];
        foreach ($roles as $roleName) {
            Role::firstOrCreate(['name' => $roleName]);
        }

        // 2️⃣ Crear usuarios
        $usersData = [
            ['name' => 'Admin User', 'email' => 'admin@example.com', 'password' => 'password', 'confirmed' => true, 'isVerified' => true, 'roles' => ['admin']],
            ['name' => 'Director User', 'email' => 'director@example.com', 'password' => 'password', 'confirmed' => true, 'isVerified' => true, 'roles' => ['director']],
            ['name' => 'Teacher User', 'email' => 'teacher@example.com', 'password' => 'password', 'confirmed' => true, 'isVerified' => true, 'roles' => ['profesor']],
            ['name' => 'Regular User', 'email' => 'user@example.com', 'password' => 'password', 'confirmed' => true, 'isVerified' => true, 'roles' => ['user']],
            ['name' => 'Super User', 'email' => 'super@example.com', 'password' => 'password', 'confirmed' => true, 'isVerified' => true, 'roles' => ['admin','director','profesor','user','psicologo']],
            ['name' => 'Inactive User', 'email' => 'inactive@example.com', 'password' => 'password', 'confirmed' => false, 'isVerified' => true, 'roles' => ['user']],
            ['name' => 'Not Verified User', 'email' => 'notverified@example.com', 'password' => 'password', 'confirmed' => true, 'isVerified' => false, 'roles' => ['user']],
        ];

        foreach ($usersData as $userData) {
            $rolesToAttach = $userData['roles'];
            unset($userData['roles']);
            $userData['password'] = Hash::make($userData['password']); // hash password

            $user = User::create($userData);

            foreach ($rolesToAttach as $roleName) {
                $role = Role::where('name', $roleName)->first();
                $user->roles()->attach($role);
            }
        }

        // 3️⃣ Crear citas de ejemplo
        Cita::create([
            'user_id' => User::first()->id,
            'fecha_cita' => now(),
            'correo' => 'student@example.com',
            'nombre_alumno' => 'Alumno Ejemplo',
            'nombre_tutor' => 'Tutor Ejemplo',
            'hora_inicio' => '10:00',
            'hora_fin' => '11:00',
        ]);

        // 4️⃣ Crear holidays de ejemplo
        Holiday::create(['date' => now()->addDays(3)]);
        Holiday::create(['date' => now()->addDays(7)]);

        // 5️⃣ Crear schedules de ejemplo
        Schedule::create(['start_time' => '08:00', 'end_time' => '18:00']);
    }
}
