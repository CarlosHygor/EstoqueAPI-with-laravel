<?php

declare(strict_types=1);

namespace App\Exceptions;

use Exception;
use Illuminate\Http\JsonResponse;

class CodigoProdutoDuplicadoException extends Exception
{
    public function __construct(string $message = 'Já existe um produto cadastrado com este código.')
    {
        parent::__construct($message);
    }

    public function render(): JsonResponse
    {
        return response()->json([
            'mensagem' => $this->getMessage(),
        ], 409);
    }
}

