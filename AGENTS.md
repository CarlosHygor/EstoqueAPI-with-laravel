# Diretrizes do Projeto EstoqueAPI com Laravel (Estudo Didático)

## Contexto do Projeto e do Desenvolvedor
- **Desenvolvedor:** Carlos Hygor (perfil júnior avançado com experiência prática em Java/Spring Boot e C#/ASP.NET Core 8).
- **Objetivo:** Adaptação/porte didático do microsserviço `Estoque.API` (originalmente em C# .NET 8 no repositório `CarlosHygor/Korp_Teste_CarlosHygor`) para **PHP 8+ e Laravel**.
- **Meta Educacional:** Aprender a sintaxe do PHP moderno e os padrões/convenções do Laravel para se preparar para um projeto freelancer em produção.

## Método de Trabalho (Pair Programming Mentor Sênior - Júnior)
1. **Passo a Passo / Camada por Camada:**
   - **NÃO** implementar a API inteira de uma vez.
   - **NÃO** ler ou buscar arquivos do repositório remoto externo automaticamente; o usuário fornecerá os blocos de código C# ou instruções diretamente nesta conversa (no máximo consultar o README se estritamente necessário).
   - Trabalhar iterativamente por blocos de código ou por camadas (ex: Migrations/Schema -> Model/Eloquent -> Form Requests/Validação -> Services/Actions -> Controllers/Rotas -> Testes).
   - O usuário envia um bloco de código (em C# ou regra) ou uma ordem específica, e o assistente implementa e explica.
2. **Postura Pedagógica:**
   - Explicar **o porquê** de cada padrão do Laravel em comparação direta com o equivalente em **Java/Spring** e **C#/ASP.NET**.
   - Destacar boas práticas: tipagem estrita (`declare(strict_types=1);`), injeção de dependências, tratamento de concorrência com transações (`DB::transaction`) e *pessimistic locking* (`lockForUpdate`).
   - Apontar particularidades do PHP/Laravel (ex: convenções de nomenclatura `snake_case` em colunas do banco vs `camelCase`/`PascalCase`, Form Requests vs DTOs, Active Record vs Data Mapper).

## Escopo da Estoque.API
- Banco de dados: PostgreSQL (`estoque_db`).
- Entidade principal: `Produto` (id, codigo, descricao, saldo, etc.).
- Princípios técnicos obrigatórios:
  - *Defense in Depth:* Validação na aplicação (`saldo >= 0`) + Check constraint física no PostgreSQL (`"saldo" >= 0`) + *Row-level locking* em abates.
  - Abate atômico em lote (`/api/produtos/abater-lote`) com suporte a chave de idempotência (`IdempotencyKey`).
  - Estorno em lote (`/api/produtos/estornar-lote`) como ação compensatória para padrão Saga.
  - Respostas HTTP padronizadas: 200, 201, 204, 400, 404, 409 (código duplicado), 422 (saldo insuficiente).

