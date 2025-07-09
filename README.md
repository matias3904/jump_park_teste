# API de Ordens de Serviço - Laravel + MySQL

Este projeto é uma API REST construída em Laravel 12, conectada a um banco de dados MySQL com suporte a testes automatizados usando Pest PHP. A API permite **criar** e **listar** ordens de serviço, com relacionamento à tabela de usuários.

---

## Requisitos para executar a aplicação

- [Composer](https://getcomposer.org/download/)
- PHP 8.2.12 (vem com o [XAMPP 8.2.12](https://www.apachefriends.org/))
- Laravel (instalação feita pelo terminal após o Composer)
- [XAMPP 8.2.12](https://www.apachefriends.org/index.html)
- [Postman](https://www.postman.com/downloads/)
- PestPHP (instalação feita pelo terminal)
- [Visual Studio Code (VSCode)](https://code.visualstudio.com/)

---

## Passo a Passo para Executar a Aplicação

### 1. Configure o ambiente

- Instale todos os requisitos listados acima.
- Abra o local de instalação do XAMPP e procure pela pasta `php`, copie o caminho (exemplo: `C:\xampp\php`) e adicione esse caminho nas variáveis de ambiente (PATH) do seu sistema.

### 2. Configure o Projeto

- Abra a pasta do projeto `service-orders-api` no VSCode.

- Baixe e instale o Composer ([link](https://getcomposer.org/download/)), e durante a instalação selecione o caminho do PHP que está dentro da pasta do XAMPP (ex: `C:\xampp\php\php.exe`).

- Renomeie o arquivo `.env.example` para `.env` para que o Laravel reconheça suas configurações de ambiente.

### 3. Configure o Banco de Dados

- Abra o XAMPP e inicie **Apache** e **MySQL**.
- Clique no botão **Admin** ao lado do MySQL para abrir o **phpMyAdmin**.
- Crie um banco de dados chamado `jump_park` e um banco chamado `jump_park_test` para teste.
- Dentro da tabela `users`, insira manualmente um registro para que os testes possam ser feitos.

### 4. Verifique as instalações no terminal do VSCode

- Execute:

```bash
php -v
composer -v
```

> Se der erro, feche e reabra o VSCode.

### 5. Instale o Laravel

- Execute:

```bash
composer global require laravel/installer
```

### 6. Conecte ao banco de dados

- Com o terminal aberto no diretório do projeto, execute:

```bash
php artisan migrate
```

### 7. Teste e Inicie o Servidor

- Execute os testes automatizados para garantir que tudo está funcionando:

```bash
php artisan test
```

- Em seguida, inicie o servidor Laravel:

```bash
php artisan serve
```

---

## Usando o Postman para testar a API

### 1. Listar Ordens de Serviço

- Abra o Postman e clique no botão `+` para criar uma nova requisição:

```
GET http://127.0.0.1:8000/service-orders
```

### 2. Criar uma Nova Ordem de Serviço

- Clique novamente no botão `+` para nova requisição:

```
POST http://127.0.0.1:8000/service-orders
```

- Vá até a aba **Body > raw > JSON** e cole o seguinte conteúdo:

```json
{
  "vehiclePlate": "SQA7564",
  "entryDateTime": "2025-07-08 14:00:00",
  "exitDateTime": "2025-07-08 15:30:00",
  "priceType": "Hora",
  "price": 50.50,
  "userId": 1
}
```

Se o servidor estiver rodando corretamente, você receberá uma resposta **200 OK** confirmando o cadastro.

Agora, retorne para a requisição **GET**, clique em **Send** e você verá os dados cadastrados listados na resposta.

---

Pronto! Sua API está configurada, conectada ao banco e pronta para receber requisições. 🚀

