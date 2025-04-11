# Livewire Test Application

Este é um projeto de exemplo utilizando **Laravel 12** e **Livewire 3** para demonstrar funcionalidades práticas e testes.

## Características do Componente Post

-   **Segurança**:
    -   Implementação da interface `Wireable` para manipulação de dados.
    -   Uso de `Policy` e `Gate` para controle de permissões.
    -   Regras de autorização para criação, edição e exclusão de posts.
-   **Validação**:
    -   Validação de campos como título, conteúdo e status de publicação.
-   **Paginação**:
    -   Paginação integrada para exibição de posts.
-   **Models**:
    -   SoftDeletes e Aplicação de Policies.
-   **Interfaces**:
    -   Camada de proteção com Wireable Interface no compoente Post.
-   **Mensagens de Feedback**:
    -   Mensagens de sucesso exibidas após operações como criação, edição e exclusão.
-   **Eventos Livewire**:
    -   Atualização da lista de posts.
-   **Testes Unitários**:
    -   Teste com PHPUnit.
-   **Ciclos de Vida do Componente**:
    -   Exemplos de todos os ciclos de vida do Livewire com saídas no Log:
        -   **`boot`**: Chamado uma vez quando o componente é inicializado.
            -   Log: `boot: Componente Livewire inicializado.`
        -   **`booted`**: Chamado após o método `boot`.
            -   Log: `booted: Componente Livewire totalmente carregado.`
        -   **`hydrate`**: Chamado antes de cada atualização do componente.
            -   Log: `hydrate: Componente hidratado.`
        -   **`dehydrate`**: Chamado após cada atualização do componente.
            -   Log: `dehydrate: Componente dessidratado.`
        -   **`updating`**: Chamado antes de uma propriedade pública ser atualizada.
            -   Log: `updating: propriedade pública ser atualizada.`
        -   **`updated`**: Chamado automaticamente quando uma propriedade pública é atualizada.
            -   Log: `A propriedade {nome_da_propriedade} foi atualizada.`
        -   **`dehydrateModalTitle`**: Chamado após a propriedade `modalTitle` ser desidratada.
            -   Log: `dehydrateModalTitle: Var modalTitle desidratada.`
        -   **`destroy`**: Chamado quando o componente é destruído.
            -   Log: `destroy: Componente Livewire destruído.`

## Como Inicializar o Projeto

1. Clone o repositório:

```bash
git clone https://github.com/unit-cesar/livewire-test.git
```

2. Acesse o diretório do projeto:

```bash
cd livewire-test
```

3. Copie o arquivo de exemplo `.env`:

```bash
cp .env.example .env
```

4. Crie o banco de dados SQLite:

```bash
touch database/database.sqlite
```

5. Instale as dependências do PHP:

```bash
composer install
```

6. Instale as dependências do Node.js:

```bash
npm install
```

7. Execute as migrações do banco de dados:

```bash
php artisan migrate
```

8. Popule o banco de dados com dados iniciais:

```bash
php artisan db:seed
```

9. Inicie o servidor de desenvolvimento:

```bash
composer run dev
```

## URL

Acesse a aplicação no navegador em:
http://localhost/posts

## Dados do Usuário Admin de Teste

-   Email: admin@example.org
-   Senha: 12345678

## Como Rodar os Testes

### Testes do Componente Post

Os testes do componente Post verificam:

-   **Criação de Posts**:
    -   Testa se um post pode ser criado com dados válidos.
-   **Edição de Posts**:
    -   Testa se um post existente pode ser editado com dados válidos.
-   **Exclusão de Posts**:
    -   Testa se um post pode ser excluído com permissões adequadas.
-   **Validação**:
    -   Testa se as regras de validação são aplicadas corretamente.

Execute os testes do componente Post com o comando:

```bash
php artisan test --filter=PostsTest
```

Execute todos testes da aplicação:

```bash
npm run testDev
```
