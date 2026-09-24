🏢 API de Gerenciamento de Visitas
API RESTful desenvolvida em Laravel para o gerenciamento corporativo de controle de acesso, envolvendo setores, colaboradores, visitantes e solicitações de visitas, com autenticação via Laravel Sanctum.

🚀 Tecnologias Utilizadas
PHP 8.x

Laravel Framework

Laravel Sanctum (Autenticação de API baseada em Tokens)

Eloquent ORM (Modelagem de dados e relacionamentos)

Faker / Factories & Seeders (Geração de dados em massa para testes)

📊 Estrutura do Banco de Dados & Entidades
O sistema é composto pelas seguintes entidades principais:

Users: Usuários administradores do sistema (Autenticação).

Setores: Departamentos da empresa (ex: TI, RH, Financeiro). Relacionamento um-para-muitos com colaboradores.

Colaboradores: Funcionários que recebem as visitas, vinculados a um setor específico.

Visitantes: Pessoas externas cadastradas no sistema com CPF único.

Solicitações de Visitas: Registro do agendamento/solicitação cruzando um Visitante e um Colaborador, contendo data/hora, motivo e status (pendente, aprovada, recusada).

🔐 Endpoints da API
Autenticação (Pública e Protegida)
POST /api/register — Registra um novo usuário e retorna o token de acesso.

POST /api/login — Realiza o login e retorna o token de acesso.

POST /api/logout — Revoga o token atual (Requer autenticação).

Recursos Protegidos (Bearer Token)
Todos os endpoints abaixo exigem o header Authorization: Bearer <seu_token> e Accept: application/json.

Setores (/api/setores)

GET /api/setores — Lista todos os setores.

POST /api/setores — Cadastra um novo setor.

GET /api/setores/{id} — Exibe um setor específico.

PUT /api/setores/{id} — Atualiza um setor.

DELETE /api/setores/{id} — Remove um setor.

Colaboradores (/api/colaboradores)

GET /api/colaboradores — Lista todos os colaboradores (com dados do setor).

POST /api/colaboradores — Cadastra um colaborador.

GET /api/colaboradores/{id} — Exibe detalhes de um colaborador.

PUT /api/colaboradores/{id} — Atualiza dados do colaborador.

DELETE /api/colaboradores/{id} — Remove um colaborador.

Visitantes (/api/visitantes)

GET /api/visitantes — Lista todos os visitantes ordenados por nome.

POST /api/visitantes — Cadastra um novo visitante.

GET /api/visitantes/{id} — Exibe detalhes do visitante.

PUT /api/visitantes/{id} — Atualiza dados do visitante.

DELETE /api/visitantes/{id} — Remove um visitante.

Solicitações de Visitas (/api/solicitacoes-visitas)

GET /api/solicitacoes-visitas — Lista todas as solicitações (com dados de visitante, colaborador e setor).

POST /api/solicitacoes-visitas — Cria uma nova solicitação de visita.

GET /api/solicitacoes-visitas/{id} — Exibe uma solicitação específica.

PUT /api/solicitacoes-visitas/{id} — Atualiza a solicitação ou o seu status.

DELETE /api/solicitacoes-visitas/{id} — Exclui uma solicitação.

⚙️ Instalação e Execução
Siga os passos abaixo para rodar o projeto localmente:

Clone o repositório:

Bash
git clone <url-do-repositorio>
cd nome-do-projeto
Instale as dependências do PHP:

Bash
composer install
Configure o arquivo de ambiente:
Duplique o arquivo .env.example para .env e configure a sua conexão com o banco de dados.

Bash
cp .env.example .env
php artisan key:generate
Execute as Migrations e os Seeders:
O sistema possui seeders automatizados que criam setores dinâmicos, dezenas de colaboradores, visitantes e solicitações de visitas.

Bash
php artisan migrate:fresh --seed
Inicie o servidor de desenvolvimento:

Bash
php artisan serve
A API estará acessível em [http://127.0.0.1:8000/api](http://127.0.0.1:8000/api)