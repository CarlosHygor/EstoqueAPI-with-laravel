<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class ProdutoNaoEncontradoException extends Exception
{
    public function __construct(string $message = 'Produto não encontrado.')
    {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'mensagem' => $this->getMessage(),
        ], 404);
    }
}

