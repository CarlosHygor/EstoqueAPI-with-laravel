# EstoqueAPI com Laravel (Estudo Didático)

> ⚠️ **Aviso de Finalidade Didática:**  
> Este repositório tem fins estritamente de estudo e transição de tecnologia. Ele consiste na reescrita e adaptação para **PHP 8+ / Laravel** do microsserviço de Estoque originalmente desenvolvido em **C# / ASP.NET Core 8**.  
> O repositório original completo contendo a arquitetura distribuída (Estoque.API + Faturamento.API + Frontend Angular 22) encontra-se em:  
> 🔗 **[CarlosHygor/Korp_Teste_CarlosHygor](https://github.com/CarlosHygor/Korp_Teste_CarlosHygor)**

---

## 🎯 Objetivo do Projeto

Migrar e adaptar as regras de negócio, resiliência e contratos da **`Estoque.API`** para o ecossistema Laravel, absorvendo:
- Sintaxe moderna do **PHP 8+** (Constructor property promotion, tipos estritos, match expressions).
- Padrões e convenções do **Laravel** (Service Container, Form Requests, Eloquent ORM, Migrations, Database Transactions).
- Paralelos conceituais com **Java/Spring Boot** e **C#/ASP.NET Core**.

---

## 📦 Escopo do Microsserviço de Estoque

O microsserviço é responsável pelo cadastro de produtos, controle de saldos em estoque, baixa atômica em lote e estornos (ações compensatórias para padrões Saga/distribuídos).

### 🛡️ Princípios de Engenharia Mantidos:
1. **Defesa em Profundidade (*Defense in Depth*)**:
   - Validação de entrada via Form Requests / Regras de Aplicação (`saldo >= 0`).
   - *Check Constraint* relacional física no PostgreSQL (`"saldo" >= 0`) via migrations.
   - *Row-Level Locking* / Transações atômicas (`DB::transaction`) para evitar concorrência e *overbooking*.
2. **Idempotência no Abate**:
   - Controle de deduplicação de requisições por chave de idempotência para evitar duplo débito em retentativas de rede.
3. **Tratamento Padronizado de Exceções**:
   - Respostas HTTP consistentes: `404 Not Found`, `409 Conflict` (código duplicado), `422 Unprocessable Entity` (saldo insuficiente).

---

## 📬 Endpoints do Microsserviço de Estoque

| Método | Endpoint | Descrição | Status HTTP |
| :--- | :--- | :--- | :--- |
| **`GET`** | `/health` | Health Check de disponibilidade | `200 OK` |
| **`GET`** | `/api/produtos` | Lista paginada com busca (`busca`) e ordenação por saldo (`ordenarPorSaldo`) | `200 OK` |
| **`GET`** | `/api/produtos/{id}` | Detalhes do produto por ID | `200 OK`, `404 NotFound` |
| **`GET`** | `/api/produtos/codigo/{codigo}` | Detalhes do produto por Código | `200 OK`, `404 NotFound` |
| **`POST`** | `/api/produtos` | Cadastra novo produto | `201 Created`, `400 BadRequest`, `409 Conflict` |
| **`PUT`** | `/api/produtos/{id}` | Atualiza dados de um produto | `204 NoContent`, `404 NotFound`, `409 Conflict` |
| **`DELETE`** | `/api/produtos/{id}` | Remove produto do estoque | `204 NoContent`, `404 NotFound` |
| **`POST`** | `/api/produtos/{codigo}/abater` | Abate de quantidade individual | `200 OK`, `422 UnprocessableEntity`, `404 NotFound` |
| **`POST`** | `/api/produtos/abater-lote` | Abate atômico de múltiplos produtos em lote | `200 OK`, `422 UnprocessableEntity`, `404 NotFound` |
| **`POST`** | `/api/produtos/estornar-lote` | Reverte/Estorna lote de produtos (Ação Compensatória) | `200 OK`, `400 BadRequest` |

---

## 👨‍💻 Desenvolvedor

- **Carlos Hygor** — [GitHub](https://github.com/CarlosHygor)