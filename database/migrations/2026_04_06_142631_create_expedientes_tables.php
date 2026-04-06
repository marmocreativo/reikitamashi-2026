<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->bigIncrements('ID_PACIENTE');
            $table->string('NOMBRE', 100);
            $table->string('APELLIDOS', 100);
            $table->date('FECHA_NACIMIENTO')->nullable();
            $table->enum('SEXO', ['masculino', 'femenino', 'otro'])->nullable();
            $table->string('TELEFONO', 20)->nullable();
            $table->string('EMAIL', 150)->nullable();
            $table->text('DIRECCION')->nullable();
            $table->text('NOTAS')->nullable();
            $table->enum('ESTADO', ['activo', 'inactivo'])->default('activo');
            $table->timestamp('FECHA_REGISTRO')->useCurrent();
        });

        Schema::create('consultas', function (Blueprint $table) {
            $table->bigIncrements('ID_CONSULTA');
            $table->unsignedBigInteger('ID_PACIENTE');
            $table->date('FECHA_CONSULTA');
            $table->text('SINTOMAS')->nullable();
            $table->text('TRATAMIENTO')->nullable();
            $table->text('NOTAS')->nullable();
            $table->timestamp('FECHA_REGISTRO')->useCurrent();

            $table->foreign('ID_PACIENTE')
                  ->references('ID_PACIENTE')
                  ->on('pacientes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('consultas');
        Schema::dropIfExists('pacientes');
    }
};