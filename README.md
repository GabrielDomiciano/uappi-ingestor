# Uappi Ingestor - Adaptador Serverless

Este projeto atua como um middleware serverless, recebendo requisições de plataformas (como a VTEX), tratando e adaptando os dados, e enviando-os para a API da Uappi. Ele é projetado para ser executado em ambientes de Functions (ex: Digital Ocean App Platform).

## Arquitetura Serverless

O projeto segue um padrão onde cada endpoint é uma função PHP independente. Para compatibilidade com plataformas serverless, cada arquivo de endpoint deve obrigatoriamente conter uma função `main(array $args): array` que serve como ponto de entrada.

- **`src/`**: Contém toda a lógica de negócio em classes (Services, Mappers, Handlers).
- **`public/`**: Contém os arquivos de endpoint. Cada arquivo aqui representa uma função serverless.
- **`index.php`**: **Apenas para desenvolvimento local.** É um roteador que simula o ambiente serverless, permitindo testar os endpoints da pasta `public/`.
- **`build.sh`**: Script para empacotar as funções para o deploy.
- **`.env.example`**: Arquivo de exemplo para as variáveis de ambiente.

---

## Como Começar

### 1. Pré-requisitos
- PHP 8.0+
- Composer

### 2. Instalação

Clone o repositório e instale as dependências:

```bash
composer install
```

### 3. Variáveis de Ambiente

Copie o arquivo de exemplo e preencha com suas credenciais da Uappi:

```bash
cp .env.example .env
# Edite o arquivo .env com seus dados
```

---

## Desenvolvimento

### Criando um Novo Endpoint

1.  Crie um novo arquivo PHP dentro do diretório `public/`, seguindo a estrutura de URL desejada (ex: `public/vtex/categories/create.php`).
2.  Dentro deste arquivo, toda a sua lógica **deve** estar encapsulada em uma função com a seguinte assinatura:

    ```php
    function main(array $args): array {
        // Sua lógica aqui...
        // A plataforma passa o corpo da requisição no array $args.

        // Utilize os handlers para processar e retornar a resposta.
        $responseHandler = new \Src\Http\ResponseHandler();
        return $responseHandler->send(['message' => 'Sucesso']);
    }
    ```

3.  **Importante:** A função `main` deve sempre retornar um array formatado pelo `ResponseHandler`, que gera a estrutura esperada pela plataforma serverless.

### Teste Local

Para testar suas funções localmente, utilize o roteador `index.php` com o servidor embutido do PHP:

```bash
php -S localhost:8000
```

Agora, você pode fazer requisições para as URLs correspondentes aos seus arquivos em `public/`. O `index.php` irá interceptar a chamada, executar a função `main` correspondente e devolver uma resposta HTTP real.

**Exemplo de teste com cURL:**

```bash
curl -X POST -H "Content-Type: application/json" \
-d '{"Name": "Camiseta Teste", "RefId": "SKU123"}' \
http://localhost:8000/vtex/products/create
```

---

## Deploy

Após testar, execute o script de build para gerar os pacotes de deploy:

```bash
./build.sh
```

Este comando irá criar os arquivos prontos para o deploy na pasta `build/`.

1.  Acesse sua plataforma de Functions (Digital Ocean).
2.  Faça o upload do arquivo gerado em `build/` correspondente à função que você quer implantar.
3.  Configure as mesmas variáveis de ambiente do seu arquivo `.env` no painel de controle da sua função na plataforma ou substitua manualmente.

---

## Contato
- **Gabriel Domiciano:** gabriel.ads18@gmail.com