<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class EstoqueInsuficienteException extends Exception
{
    public function __construct(
        public readonly string $codigoProduto,
        public readonly int $saldoAtual,
        public readonly int $quantidadeSolicitada,
        string $message = 'Saldo insuficiente em estoque para o produto.'
    ) {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'mensagem' => $this->getMessage(),
            'codigoProduto' => $this->codigoProduto,
            'saldoAtual' => $this->saldoAtual,
            'quantidadeSolicitada' => $this->quantidadeSolicitada,
        ], 422);
    }
}

