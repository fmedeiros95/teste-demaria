# Teste DeMaria | Cadastro de Vendas

## Credenciais
**E-mail:** admin@example.com  
**Senha:** admin

## Pré-requisitos
- PHP 7.2 (*curl, gd, bbstring, openssl, pgsql, zip and xml*)
- PostgreSQL 12.9
- Composer 2+

## Dependências
Em caso de novo servidor, ter instalado o composer.
Entrar na pastado projeto e executar o comando:

```shell
composer install
```

> ***Nunca se deve copiar a pasta "vendor". Ela sempre deve ser instalada pois dependencias nativas podem quebrar.***

> ***Nunca comitar a pasta "vendor" de forma forçada. Não é boa prática e não faz sentido armazenar dados de depenências.***

## Configurando
Execute o comando abaixo para criar o arquivo **.env**.

Após criado, preencha o mesmo com as credenciais de acesso ao seu banco de dados
```shell
php -r "copy('.env.example', '.env');"
```

## Executando
Para executar o projeto, execute os comandos abaixo, obedecendo a ordem
```shell
php artisan key:generate	# Gera a chave da aplicação
php artisan migrate		# Roda as migrações para o banco de dados
php artisan db:seed		# Cria uma conta de admin
php artisan serve		# Inicia a aplicação
```

**ACESSE:**  http://localhost:8000
