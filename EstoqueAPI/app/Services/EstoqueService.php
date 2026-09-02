<?php

declare(strict_types=1);

namespace App\Services;

use App\Exceptions\CodigoProdutoDuplicadoException;
use App\Exceptions\EstoqueInsuficienteException;
use App\Exceptions\ProdutoNaoEncontradoException;
use App\Models\ProcessamentoIdempotente;
use App\Models\Produto;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\QueryException;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

class EstoqueService
{
    public function getPaginated(?int $pagina = 1, ?int $tamanhoPagina = 10, ?string $ordenarPorSaldo = null, ?string $busca = null): LengthAwarePaginator
    {
        $paginaValida = max($pagina ?? 1, 1);
        $tamanhoValido = min(max($tamanhoPagina ?? 10, 1), 100);

        return Produto::query()
            ->busca($busca)
            ->ordenarPorSaldo($ordenarPorSaldo)
            ->paginate(perPage: $tamanhoValido, page: $paginaValida);
    }

    public function getById(int $id): Produto
    {
        $produto = Produto::find($id);

        if (!$produto) {
            throw new ProdutoNaoEncontradoException();
        }

        return $produto;
    }

    public function getByCodigo(string $codigo): Produto
    {
        if (blank($codigo)) {
            throw new \InvalidArgumentException('O código do produto não pode ser nulo ou vazio.');
        }

        $produto = Produto::where('codigo', $codigo)->first();

        if (!$produto) {
            throw new ProdutoNaoEncontradoException();
        }

        return $produto;
    }

    public function create(array $dados): Produto
    {
        $this->validarDadosProduto($dados);

        try {
            return Produto::create($dados);
        } catch (UniqueConstraintViolationException | QueryException $e) {
            if ($e instanceof UniqueConstraintViolationException || $e->getCode() === '23505' || str_contains($e->getMessage(), 'UNIQUE')) {
                throw new CodigoProdutoDuplicadoException();
            }
            throw $e;
        }
    }

    public function update(int $id, array $dados): Produto
    {
        $this->validarDadosProduto($dados);

        $produto = $this->getById($id);

        try {
            $produto->update($dados);
            return $produto;
        } catch (UniqueConstraintViolationException | QueryException $e) {
            if ($e instanceof UniqueConstraintViolationException || $e->getCode() === '23505' || str_contains($e->getMessage(), 'UNIQUE')) {
                throw new CodigoProdutoDuplicadoException();
            }
            throw $e;
        }
    }

    public function delete(int $id): void
    {
        $produto = $this->getById($id);
        $produto->delete();
    }

    // Abate individual com trava de concorrência (row-level locking)
    public function abaterEstoque(string $codigo, int $quantidade): void
    {
        if (blank($codigo)) {
            throw new \InvalidArgumentException('O código do produto deve ser informado.');
        }

        if ($quantidade <= 0) {
            throw new \InvalidArgumentException('A quantidade a abater deve ser maior que zero.');
        }

        $produto = Produto::where('codigo', $codigo)->lockForUpdate()->first();

        if (!$produto) {
            throw new ProdutoNaoEncontradoException();
        }

        if ($produto->saldo < $quantidade) {
            throw new EstoqueInsuficienteException(
                codigoProduto: $codigo,
                saldoAtual: $produto->saldo,
                quantidadeSolicitada: $quantidade
            );
        }

        $produto->saldo -= $quantidade;
        $produto->save();
    }

    // Abate em lote com suporte a chave de idempotência
    public function abaterEstoqueLote(array $itens, ?string $idempotencyKey = null): bool
    {
        if (empty($itens)) {
            throw new \InvalidArgumentException('A lista de itens para abate de estoque não pode estar vazia.');
        }

        return DB::transaction(function () use ($itens, $idempotencyKey) {
            if (!blank($idempotencyKey)) {
                $jaProcessado = ProcessamentoIdempotente::where('chave', $idempotencyKey)->exists();
                if ($jaProcessado) {
                    return false;
                }
            }

            foreach ($itens as $item) {
                $codigo = $item['codigo'] ?? $item['codigoProduto'] ?? '';
                $quantidade = (int) ($item['quantidade'] ?? 0);
                $this->abaterEstoque($codigo, $quantidade);
            }

            if (!blank($idempotencyKey)) {
                ProcessamentoIdempotente::create(['chave' => $idempotencyKey]);
            }

            return true;
        });
    }

    public function estornarEstoque(string $codigo, int $quantidade): void
    {
        if (blank($codigo)) {
            throw new \InvalidArgumentException('O código do produto deve ser informado.');
        }

        if ($quantidade <= 0) {
            throw new \InvalidArgumentException('A quantidade a estornar deve ser maior que zero.');
        }

        $produto = Produto::where('codigo', $codigo)->lockForUpdate()->first();

        if (!$produto) {
            throw new ProdutoNaoEncontradoException();
        }

        $produto->saldo += $quantidade;
        $produto->save();
    }

    // Estorno em lote como ação compensatória (Padrão Saga)
    public function estornarEstoqueLote(array $itens): void
    {
        if (empty($itens)) {
            return;
        }

        DB::transaction(function () use ($itens) {
            foreach ($itens as $item) {
                $codigo = $item['codigo'] ?? $item['codigoProduto'] ?? '';
                $quantidade = (int) ($item['quantidade'] ?? 0);
                $this->estornarEstoque($codigo, $quantidade);
            }
        });
    }

    private function validarDadosProduto(array $dados): void
    {
        if (blank($dados['codigo'] ?? null)) {
            throw new \InvalidArgumentException('O código do produto é um campo obrigatório.');
        }

        if (blank($dados['descricao'] ?? null)) {
            throw new \InvalidArgumentException('A descrição do produto é um campo obrigatório.');
        }

        if (($dados['saldo'] ?? 0) < 0) {
            throw new \InvalidArgumentException('O saldo do produto não pode ser negativo.');
        }
    }
}
