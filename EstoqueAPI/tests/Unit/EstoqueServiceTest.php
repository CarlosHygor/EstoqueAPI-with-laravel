<?php

declare(strict_types=1);

namespace Tests\Unit;

use App\Exceptions\CodigoProdutoDuplicadoException;
use App\Exceptions\EstoqueInsuficienteException;
use App\Exceptions\ProdutoNaoEncontradoException;
use App\Models\Produto;
use App\Services\EstoqueService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EstoqueServiceTest extends TestCase
{
    use RefreshDatabase;

    private EstoqueService $estoqueService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->estoqueService = new EstoqueService();
    }

    public function test_abater_estoque_com_sucesso(): void
    {
        Produto::create([
            'codigo' => 'PROD-001',
            'descricao' => 'Teclado Mecânico',
            'saldo' => 10,
        ]);

        $this->estoqueService->abaterEstoque('PROD-001', 2);

        $produto = Produto::where('codigo', 'PROD-001')->first();
        $this->assertEquals(8, $produto->saldo);
    }

    public function test_abater_estoque_com_saldo_insuficiente_lanca_excecao(): void
    {
        Produto::create([
            'codigo' => 'PROD-001',
            'descricao' => 'Teclado Mecânico',
            'saldo' => 5,
        ]);

        $this->expectException(EstoqueInsuficienteException::class);

        try {
            $this->estoqueService->abaterEstoque('PROD-001', 10);
        } finally {
            $produto = Produto::where('codigo', 'PROD-001')->first();
            $this->assertEquals(5, $produto->saldo);
        }
    }

    public function test_abater_estoque_produto_inexistente_lanca_excecao(): void
    {
        $this->expectException(ProdutoNaoEncontradoException::class);
        $this->estoqueService->abaterEstoque('PROD-INEXISTENTE', 1);
    }

    public function test_abater_estoque_lote_com_sucesso(): void
    {
        Produto::create(['codigo' => 'ATOMIC-01', 'descricao' => 'Produto 1', 'saldo' => 10]);
        Produto::create(['codigo' => 'ATOMIC-02', 'descricao' => 'Produto 2', 'saldo' => 10]);

        $itens = [
            ['codigo' => 'ATOMIC-01', 'quantidade' => 3],
            ['codigo' => 'ATOMIC-02', 'quantidade' => 4],
        ];

        $resultado = $this->estoqueService->abaterEstoqueLote($itens, 'KEY-001');

        $this->assertTrue($resultado);
        $this->assertEquals(7, Produto::where('codigo', 'ATOMIC-01')->first()->saldo);
        $this->assertEquals(6, Produto::where('codigo', 'ATOMIC-02')->first()->saldo);
    }

    public function test_abater_estoque_lote_idempotente_retorna_false_na_segunda_execucao(): void
    {
        Produto::create(['codigo' => 'ATOMIC-01', 'descricao' => 'Produto 1', 'saldo' => 10]);

        $itens = [['codigo' => 'ATOMIC-01', 'quantidade' => 2]];

        $primeiraExecucao = $this->estoqueService->abaterEstoqueLote($itens, 'KEY-IDEMPOTENT-1');
        $segundaExecucao = $this->estoqueService->abaterEstoqueLote($itens, 'KEY-IDEMPOTENT-1');

        $this->assertTrue($primeiraExecucao);
        $this->assertFalse($segundaExecucao);
        $this->assertEquals(8, Produto::where('codigo', 'ATOMIC-01')->first()->saldo);
    }

    public function test_abater_estoque_lote_atomico_com_item_inexistente_faz_rollback_de_todos(): void
    {
        Produto::create(['codigo' => 'ATOMIC-01', 'descricao' => 'Produto 1', 'saldo' => 10]);

        $itens = [
            ['codigo' => 'ATOMIC-01', 'quantidade' => 3],
            ['codigo' => 'ITEM-INEXISTENTE', 'quantidade' => 2],
        ];

        try {
            $this->estoqueService->abaterEstoqueLote($itens);
        } catch (ProdutoNaoEncontradoException $e) {
            // Captura a exceção esperada para checar a atomicidade do rollback
        }

        $this->assertEquals(10, Produto::where('codigo', 'ATOMIC-01')->first()->saldo);
    }

    public function test_estornar_estoque_com_sucesso(): void
    {
        Produto::create(['codigo' => 'PROD-001', 'descricao' => 'Teclado Mecânico', 'saldo' => 10]);

        $this->estoqueService->estornarEstoque('PROD-001', 5);

        $this->assertEquals(15, Produto::where('codigo', 'PROD-001')->first()->saldo);
    }

    public function test_estornar_estoque_lote_com_sucesso(): void
    {
        Produto::create(['codigo' => 'PROD-001', 'descricao' => 'Produto 1', 'saldo' => 10]);
        Produto::create(['codigo' => 'PROD-002', 'descricao' => 'Produto 2', 'saldo' => 10]);

        $itens = [
            ['codigo' => 'PROD-001', 'quantidade' => 5],
            ['codigo' => 'PROD-002', 'quantidade' => 10],
        ];

        $this->estoqueService->estornarEstoqueLote($itens);

        $this->assertEquals(15, Produto::where('codigo', 'PROD-001')->first()->saldo);
        $this->assertEquals(20, Produto::where('codigo', 'PROD-002')->first()->saldo);
    }

    public function test_cadastrar_produto_com_codigo_duplicado_lanca_excecao(): void
    {
        Produto::create(['codigo' => 'DUPLICADO-01', 'descricao' => 'Produto 1', 'saldo' => 10]);

        $this->expectException(CodigoProdutoDuplicadoException::class);

        $this->estoqueService->create([
            'codigo' => 'DUPLICADO-01',
            'descricao' => 'Outro Produto com mesmo codigo',
            'saldo' => 5,
        ]);
    }
}

