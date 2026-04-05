<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        Schema::table('categorias', function (Blueprint $table) {
            $table->boolean('DESTACADA')->default(false)->after('ORDEN');
        });
    }

    public function down(): void
    {
        DB::statement("SET SESSION sql_mode = ''");

        Schema::table('categorias', function (Blueprint $table) {
            $table->dropColumn('DESTACADA');
        });
    }
};