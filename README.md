# API de Gerenciamento de Visitas

API RESTful em Laravel para controle de acesso corporativo: setores, colaboradores, visitantes e solicitações de visita, com autenticação via Laravel Sanctum e ambiente dockerizado.

## Tecnologias

- PHP 8.x e Laravel
- Laravel Sanctum (autenticação por token)
- Scramble (documentação OpenAPI automática)
- Eloquent ORM, Factories e Seeders
- Docker e Docker Compose

## Entidades

- **Users:** administradores do sistema (autenticação)
- **Setores:** departamentos da empresa (ex.: TI, RH, Financeiro)
- **Colaboradores:** funcionários que recebem visitas, vinculados a um setor
- **Visitantes:** pessoas externas, com CPF único
- **Solicitações de visitas:** relacionam visitante e colaborador, com data/hora, motivo e status (pendente, aprovada ou recusada)

## Endpoints

**Autenticação**

| Método | Rota | Descrição |
|--------|------|-----------|
| POST | `/api/register` | Registra usuário e retorna o token |
| POST | `/api/login` | Autentica e retorna o token |
| POST | `/api/logout` | Revoga o token atual (autenticado) |

**Recursos protegidos** (headers `Authorization: Bearer <token>` e `Accept: application/json`)

Cada recurso oferece CRUD completo: `GET` (lista), `POST`, `GET /{id}`, `PUT /{id}` e `DELETE /{id}`.

| Recurso | Rota base | Observação |
|---------|-----------|------------|
| Setores | `/api/setores` | |
| Colaboradores | `/api/colaboradores` | Lista inclui dados do setor |
| Visitantes | `/api/visitantes` | Lista ordenada por nome |
| Solicitações de visitas | `/api/solicitacoes-visitas` | Lista inclui visitante, colaborador e setor; `PUT` também atualiza o status |

## Documentação interativa

Gerada automaticamente pelo [Scramble](https://scramble.dedoc.co/), com a aplicação em execução:

- Interface: http://127.0.0.1:8000/docs/api
- OpenAPI (JSON): http://127.0.0.1:8000/docs/api.json

Para testar rotas protegidas, informe um token Bearer do Sanctum. Fora do ambiente `local`, o acesso depende da autorização `viewApiDocs`.

## Instalação

### Com Docker (recomendado)

```bash
git clone <url-do-repositorio>
cd <nome-do-projeto>

cp .env.example .env
docker compose up -d --build

docker compose exec app composer install
docker compose exec app php artisan key:generate
docker compose exec app php artisan migrate:fresh --seed
```

A API estará em http://127.0.0.1:8000/api.

### Sem Docker

Requisitos: PHP 8.x, Composer e MySQL.

```bash
git clone <url-do-repositorio>
cd <nome-do-projeto>

composer install
cp .env.example .env
# configure o banco no .env

php artisan key:generate
php artisan migrate:fresh --seed
php artisan serve
```

> Os seeders criam setores, dezenas de colaboradores, visitantes e solicitações de teste. `migrate:fresh` apaga todas as tabelas, então use só em desenvolvimento.