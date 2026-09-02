<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('produtos', function (Blueprint $table) {
            $table->id();
            $table->string('codigo')->unique();
            $table->string('descricao');
            $table->integer('saldo');
            $table->timestamps();
        });

        // Defense in Depth: Check Constraint física no PostgreSQL para impedir saldo negativo
        DB::statement('ALTER TABLE produtos ADD CONSTRAINT CK_produtos_saldo CHECK (saldo >= 0)');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produtos');
    }
};

