<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Model Produto
 * 
 * @property int $id
 * @property string $codigo
 * @property string $descricao
 * @property int $saldo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class Produto extends Model
{
    use HasFactory;

    /**
     * Os atributos que podem ser atribuídos em massa (Mass Assignment).
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo',
        'descricao',
        'saldo',
    ];

    /**
     * Mapeamento de tipos dos atributos.
     * @var array<string, string>
     */
    protected $casts = [
        'saldo' => 'integer',
    ];
}

