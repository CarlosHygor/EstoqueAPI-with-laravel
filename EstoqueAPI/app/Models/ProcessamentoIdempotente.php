<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $chave
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 */
class ProcessamentoIdempotente extends Model
{
    use HasFactory;

    protected $table = 'processamentos_idempotentes';

    protected $fillable = [
        'chave',
    ];
}

