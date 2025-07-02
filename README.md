# ✈️ Onfly Travel

Bem-vindo à Onfly Travel, uma aplicação completa para gerenciamento de viagens, construída com um backend robusto em Laravel e um frontend reativo em Vue.js. Todo o ambiente de desenvolvimento é containerizado com Docker, garantindo uma configuração rápida e consistente.

## Sobre o Projeto

O projeto foi desenvolvido para um desafio técnico.

## Tecnologias Utilizadas

* **Laravel:** Para construção da API/backend
* **Pest:** Para testes automatizados
* **MySQL:** Para o banco de dados
* **Vue:** Para construção do frontend
* **Pinia:** Para estados globais
* **Vue Router:** Para roteamento da SPA
* **Axios:** Para requisições via protocolo HTTP
* **Nginx:** Servidor Web para servir a API e o frontend
* **Docker:** Como ambiente de desenvolvimento (dockerização)

## Pré-requisitos

Antes de começar, certifique-se de que você tem as seguintes ferramentas instaladas em sua máquina:

* [Git](https://git-scm.com/)
* [Docker](https://www.docker.com/products/docker-desktop/)
* [Docker Compose](https://docs.docker.com/compose/install/) (geralmente já vem incluído no Docker Desktop)

## Como Configurar e Executar

Siga os passos abaixo para configurar e executar a aplicação em seu ambiente local.

### 1. Clonar o Repositório

Primeiro, clone o repositório do projeto para a sua máquina local e navegue para a pasta do projeto.

- Caso utilize HTTP:
```bash
git clone https://github.com/csarfau/onfly-challenge.git
```

- Ou se tiver utilizando SSH:
```bash
git clone git@github.com:csarfau/onfly-challenge.git
```

- Entre na pasta do projeto
```bash
cd onfly-challenge
```

### 2. Configurar o Ambiente Backend

A aplicação Laravel precisa de um arquivo de variáveis de ambiente (`.env`). Copie o arquivo de exemplo para criar o seu próprio.

```bash
cp ./backend/.env.example ./backend/.env
```

> [!NOTE]
> As variáveis de ambiente já estão prontas para funcionar localmente com Docker, caso deseje alterar nome do banco de dados ou senha de conexão
> deve ser alterado tanto no .env (DB_DATABASE, DB_PASSWORD), quanto as variáveis no arquivo docker-compose.yml (MYSQL_DATABASE, MYSQL_ROOT_PASSWORD) com os mesmos valores respectivamente.

### 3. Construir e Iniciar os Containers

Com o Docker em execução na sua máquina, utilize o Docker Compose para construir as imagens e iniciar todos os serviços (backend, frontend, banco de dados).

```bash
docker-compose up -d --build
```

### 4. Finalizar a Configuração do Backend

Após os containers estarem em pé, precisamos executar alguns comandos dentro do container do backend para finalizar a instalação do Laravel.

O nome do serviço do backend no `docker-compose.yml` é `onfly_challenge_backend`.

### 1. Instalar as dependências do PHP com o Composer
```bash
docker-compose exec onfly_challenge_backend composer install
```

### 2. Gerar a chave de aplicação do Laravel (APP_KEY)
```bash
docker-compose exec onfly_challenge_backend php artisan key:generate
```

### 3. Gerar o segredo para a autenticação JWT
```bash
docker-compose exec onfly_challenge_backend php artisan jwt:secret
```

# 4. Executar as migrações do banco de dados e popular com dados iniciais (seeders)
```bash
docker-compose exec onfly_challenge_backend php artisan migrate --force --seed
```

## Acessando a Aplicação

Se tudo deu certo até aqui, você poderá acessar a aplicação no endereço:
> http://localhost:6162 

> [!IMPORTANT]
> Para testar as funcionalidades de Admin, foi criado um usuário padrão para isso, com as seguintes credenciais:
> email: admin@example.com
> senha: password

## Rodando testes
Para rodar os testes, utilize o seguinte comando:
```bash
docker-compose exec onfly_challenge_backend php artisan test
```
