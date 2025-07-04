# Adapter Serverless

Este projeto converte requisições de ERPs para o formato esperado pela API Uappi, usando funções serverless PHP.

## Estrutura

- `src/` — código PHP modularizado (mappers, services)
- `public/` — endpoints públicos, scripts PHP que atuam como handlers
- `build/` — scripts gerados prontos para deploy serverless (um arquivo por endpoint)
- `build.sh` — script que gera os arquivos em `build/`
- `.env.example` — Exemplo de arquivo de configuração de variáveis de ambiente.
- `index.php` — Ponto de entrada para execução local do projeto.

## Como usar

1.  **Instalação das Dependências:**
    Certifique-se de ter o Composer instalado. Na raiz do projeto, execute:
    ```bash
    composer install
    ```

2.  **Configuração das Variáveis de Ambiente:**
    Crie um arquivo `.env` na raiz do projeto, copiando o `.env.example` e preenchendo com suas credenciais:
    ```bash
    cp .env.example .env
    # Edite o arquivo .env com suas credenciais reais
    ```

3.  **Execução Local (para desenvolvimento):**
    Para testar os endpoints localmente, inicie o servidor web embutido do PHP na raiz do projeto, apontando para `index.php`:
    ```bash
    php -S localhost:8000 index.php
    ```
    Agora você pode acessar seus endpoints via `http://localhost:8000/vtex/products/create.php` (e outros que você criar em `public/`).

4.  **Geração dos Scripts para Deploy:**
    Após desenvolver e testar, rode o script de build para gerar os arquivos otimizados para o ambiente serverless:
    ```bash
    ./build.sh
    ```

5.  **Deploy:**
    Faça o deploy dos scripts gerados dentro de `build/` no seu ambiente serverless (ex: DigitalOcean Functions).
    **Importante:** As variáveis de ambiente (`UAPPI_ENDPOINT`, `UAPPI_API_KEY`, `UAPPI_SECRET_KEY`) e o carregamento das dependências (Composer/autoload) devem ser configurados diretamente na plataforma serverless, não no pacote de deploy. Para o token de autenticação, ele será gerado a cada invocação da função serverless, pois não há persistência de estado entre as execuções.

## Organização

Para cada arquivo PHP dentro de `public/`, será gerado um script único em `build/` com nome baseado no caminho.

Por exemplo:

- `public/vtex/product/create.php` → `build/vtex_product_create.php`

## Importante

- O script `build.sh` remove namespaces, `use`, tags `<?php` e concatena todo código necessário.
- Cada arquivo gerado em `build/` contém o handler completo para responder a requisições.
- Os arquivos em `public/` devem conter apenas a lógica do handler e `use` statements. **Não inclua `require` de `autoload.php` ou carregamento de `.env` diretamente neles**, pois isso é tratado pelo `index.php` em desenvolvimento.

---

## Para desenvolver

- Crie seus mappers dentro de `src/`
- Crie seus services dentro de `src/` (seguindo a estrutura `src/Services/Auth/`, `src/Services/Products/`, etc.)
- Crie handlers em PHP em `public/` para cada endpoint necessário.
- Utilize as classes da Vtex de produto como referência.

---

## Deploy

- Faça deploy do conteúdo gerado em `build/` para a sua função serverless na DigitalOcean.

---

## Contato
- **Gabriel Domiciano** -> gabriel.ads18@gmail.com
