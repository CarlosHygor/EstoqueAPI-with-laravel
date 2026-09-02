<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $codigo
 * @property string $descricao
 * @property int $saldo
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * 
 * @method static Builder busca(?string $busca)
 * @method static Builder ordenarPorSaldo(?string $ordem)
 */
class Produto extends Model
{
    use HasFactory;

    /**
     * @var array<int, string>
     */
    protected $fillable = [
        'codigo',
        'descricao',
        'saldo',
    ];

    /**
     * @var array<string, string>
     */
    protected $casts = [
        'saldo' => 'integer',
    ];

    // Filtra produtos por código ou descrição.
    public function scopeBusca(Builder $query, ?string $busca): Builder
    {
        if (blank($busca)) {
            return $query;
        }

        $termo = strtolower(trim($busca));

        return $query->where(function (Builder $q) use ($termo) {
            $q->whereRaw('LOWER(codigo) LIKE ?', ["%{$termo}%"])
              ->orWhereRaw('LOWER(descricao) LIKE ?', ["%{$termo}%"]);
        });
    }

    // Ordena produtos por saldo ou por ID como padrão.
    public function scopeOrdenarPorSaldo(Builder $query, ?string $ordem): Builder
    {
        return match (strtolower((string) $ordem)) {
            'asc' => $query->orderBy('saldo', 'asc'),
            'desc' => $query->orderBy('saldo', 'desc'),
            default => $query->orderBy('id', 'asc'),
        };
    }
}