<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('alumnos', function (Blueprint $table) {
            $table->id();
            $table->string('nombre_completo');
            $table->date('fecha_nacimiento');
            $table->integer('edad');
            $table->enum('sexo', ['M', 'F', 'Otro']);
            $table->integer('num_hermanos')->nullable();
            $table->integer('num_hijos')->nullable();
            $table->string('tipo_preescolar')->nullable();
            $table->string('nombre_preescolar')->nullable();
            $table->string('tipo_primaria')->nullable();
            $table->string('nombre_primaria')->nullable();
            $table->string('duracion_tiempo_preescolar')->nullable();
            $table->string('duracion_tiempo_primaria')->nullable();
            $table->string('tipo_secundaria')->nullable();
            $table->string('nombre_secundaria')->nullable();
            $table->string('duracion_tiempo_secundaria')->nullable();
            $table->string('nss')->nullable();
            $table->string('tipo_de_sangre')->nullable();
            $table->text('enfermedades')->nullable();
            $table->text('alergias')->nullable();
            $table->text('medicamentos')->nullable();
            $table->text('operaciones')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alumnos');
    }
};
