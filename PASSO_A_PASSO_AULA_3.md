# Aula 3 – Autenticação e cadastro

Passo a passo da Aula 3: login, cadastro, logout e sessão. O menu do header e a página do mapa mudam conforme o usuário está logado ou não.

---

## Ordem do que fazer

| # | O que fazer | Onde |
|---|-------------|------|
| 1 | Criar a branch `aula-3` | Git |
| 2 | Iniciar sessão e carregar conexão no `index.php` | `index.php` |
| 3 | Criar `UsuarioModel` (buscar por email, criar usuário) | `app/Models/UsuarioModel.php` |
| 4 | Criar `AuthController` (login, logout, register) | `app/Controllers/AuthController.php` |
| 5 | Criar views de login e cadastro (formulários POST) | `app/Views/auth/login.php`, `register.php` |
| 6 | Atualizar o header (rotas MVC + menu quando logado) | `includes/header.php` |
| 7 | Colocar classe `user-logado` no body quando logado | `includes/header.php` |
| 8 | Ajustar o JS para não remover a classe do body | `assets/js/app.js` |
| 9 | Remover a pasta `app/auth/` (evitar duplicação; ver Passo 9) | Apagar `app/auth/login.php` e `register.php` |
| 10 | Esconder “Faça login” no mapa quando logado | `app/Views/mapa.php` |
| 11 | Testar cadastro, login, logout e menu | Navegador |
| 12 | Commit da Aula 3 | Git |

---

## O que a Aula 3 entrega

- **Sessão PHP** no `index.php` para guardar `usuario_id` e `usuario_nome`.
- **UsuarioModel**: `buscarPorEmail()` (login) e `criar()` (cadastro com `password_hash`).
- **AuthController**: ações `login`, `logout` e `register`; rotas `?c=auth&a=login`, etc.
- **Views** de login e cadastro com formulários POST e mensagens de erro/sucesso na sessão.
- **Header** com links para as rotas MVC; quando logado, exibe menu (Mapa, Minhas Reservas, Nova Reserva) e “Olá, Nome” + Sair.
- **Body** com classe `user-logado` quando logado, para o CSS exibir o menu (o JS não remove mais essa classe).
- **Página do mapa**: texto “Faça login para reservar” só para visitante; quando logado, só “Todas as salas disponíveis para reserva.”
- **Uma só pasta de auth**: as telas de login e cadastro ficam apenas em `app/Views/auth/`. A pasta antiga `app/auth/` foi removida para não duplicar (todos os links usam as rotas MVC).

Na **Aula 4** você usará `$_SESSION['usuario_id']` para criar uma reserva (formulário, validação e INSERT na tabela `reservas`).

---

## Passo 1 – Criar a branch

```bash
git checkout -b aula-3
```

---

## Passo 2 – Sessão e conexão no index.php

No `index.php`, após carregar o `config.php`:

1. **Sessão:** chamar `session_start()` se ainda não houver sessão ativa (para guardar o usuário logado).
2. **Conexão:** dar `require_once CONFIG_PATH . '/conexao.php'` para que o `$pdo` exista antes de qualquer controller (Auth e Models usam o banco).

---

## Passo 3 – UsuarioModel

Criar `app/Models/UsuarioModel.php`:

- **Construtor:** guardar a conexão PDO no objeto (`global $pdo` → `$this->pdo`).
- **buscarPorEmail(string $email):** `SELECT id, nome, email, senha FROM usuarios WHERE email = ?` com prepared statement; retornar uma linha (array) ou `null`.
- **criar(string $nome, string $email, string $senhaHash):** `INSERT INTO usuarios (nome, email, senha)` com prepared statement. A senha deve vir já hasheada com `password_hash($senha, PASSWORD_DEFAULT)` no controller.

---

## Passo 4 – AuthController

Criar `app/Controllers/AuthController.php`:

- **urlIndex():** método privado que monta a URL do `index.php` (para funcionar na raiz ou em subpasta).
- **login():**
  - **POST:** pegar `email` e `senha` do `$_POST`; validar; `UsuarioModel->buscarPorEmail($email)`; `password_verify($senha, $usuario['senha'])`; em sucesso, gravar `$_SESSION['usuario_id']` e `$_SESSION['usuario_nome']` e redirecionar para o mapa; em falha, gravar `$_SESSION['erro_login']` e redirecionar para a tela de login.
  - **GET:** incluir a view `app/Views/auth/login.php`.
- **logout():** limpar `$_SESSION`, destruir o cookie de sessão, `session_destroy()` e redirecionar para o mapa.
- **register():**
  - **POST:** pegar `nome`, `email`, `senha`, `confirmar_senha`; validar (todos preenchidos, senhas iguais, senha com no mínimo 6 caracteres); `UsuarioModel->criar($nome, $email, password_hash($senha, PASSWORD_DEFAULT))`; em sucesso, redirecionar para login com mensagem de sucesso; em erro (ex.: email duplicado), gravar `$_SESSION['erro_cadastro']` e redirecionar para a tela de cadastro.
  - **GET:** incluir a view `app/Views/auth/register.php`.

---

## Passo 5 – Views de login e cadastro

- **app/Views/auth/login.php:** formulário `method="post"` e `action` apontando para `index.php?c=auth&a=login`; campos `name="email"` e `name="senha"`; exibir `$_SESSION['erro_login']` e `$_SESSION['sucesso_cadastro']` (e dar `unset` após exibir); links para cadastro e “Voltar ao mapa”.
- **app/Views/auth/register.php:** formulário `method="post"` e `action` para `index.php?c=auth&a=register`; campos `name="nome"`, `email`, `senha`, `confirmar_senha`; exibir `$_SESSION['erro_cadastro']` e dar `unset`; link para login.

Usar `BASE_URL` (ou a constante do projeto) para montar as URLs dos formulários e dos links.

---

## Passo 6 – Header com rotas MVC e menu condicional

No `includes/header.php`:

- Montar as URLs sempre com o MVC: `index.php?c=auth&a=login`, `index.php?c=auth&a=logout`, etc. (nunca `app/auth/login.php`).
- Se **não** logado (`!isset($_SESSION['usuario_id'])`): exibir só o link “Entrar”.
- Se **logado:** exibir “Mapa de Salas”, “Minhas Reservas”, botão “Nova Reserva” e o bloco “Olá, **Nome**” + “Sair” (usar `$_SESSION['usuario_nome']`).

---

## Passo 7 – Classe user-logado no body

O CSS em `app.css` usa `body.user-logado` para exibir o menu do usuário (`.nav-logado`) e esconder o link “Entrar” (`.nav-guest`). No `header.php`, ao abrir a tag `<body>`, adicionar a classe quando o usuário estiver logado, por exemplo:

```php
<body<?= $logado ? ' class="user-logado"' : '' ?>>
```

---

## Passo 8 – Ajustar o JavaScript (app.js)

O arquivo `assets/js/app.js` tinha uma função que **removia** a classe `user-logado` do body quando `sessionStorage.getItem('logado')` estava vazio. Como o login passou a ser por **sessão PHP**, o `sessionStorage` não é mais usado e essa remoção fazia o menu sumir após o carregamento. Ajustar para **não remover** a classe `user-logado`; apenas adicionar se existir `sessionStorage.getItem('logado')` (opcional, para compatibilidade). O estado “logado” fica definido pelo PHP (classe no body).

---

## Passo 9 – Remover a pasta app/auth/ (evitar duplicação)

Antes existiam duas pastas relacionadas a auth:

- **app/auth/** – arquivos antigos `login.php` e `register.php` (viraram só redirecionamento).
- **app/Views/auth/** – views do MVC (formulários de login e cadastro usados pelo AuthController).

Para não ter duas pastas “auth” e evitar confusão, **remova a pasta app/auth/**:

1. Apague o arquivo `app/auth/login.php`.
2. Apague o arquivo `app/auth/register.php`.
3. Se a pasta `app/auth/` ficar vazia, apague a pasta também.

**Por que não quebra:** todo o projeto já usa as rotas do MVC (`index.php?c=auth&a=login` e `?c=auth&a=register`). O AuthController inclui as views de `app/Views/auth/`. Nenhum código faz `require` de `app/auth/login.php` ou `register.php`. Quem ainda tiver link ou favorito para `app/auth/login.php` passará a receber 404; o restante continua funcionando.

**Resumo:** fica só **app/Views/auth/** com `login.php` e `register.php` (as views). Login e cadastro são acessados sempre por `index.php?c=auth&a=login` e `index.php?c=auth&a=register`.

---

## Passo 10 – Texto “Faça login” no mapa

Na view do mapa (`app/Views/mapa.php`), o texto acima da tabela deve mudar conforme o usuário está logado ou não:

- **Logado:** exibir só “Todas as salas disponíveis para reserva.”
- **Visitante:** exibir “Todas as salas disponíveis para reserva. Faça login para reservar.” (com link para a rota de login).

Usar `isset($_SESSION['usuario_id'])` na view para decidir qual texto mostrar.

---

## Passo 11 – Testar no navegador

1. **Cadastro:** acessar a rota de cadastro, preencher o formulário e enviar; conferir se o usuário foi criado no banco e se a mensagem de sucesso aparece na tela de login.
2. **Login:** acessar a rota de login, informar email e senha; conferir se o header muda (menu + “Olá, Nome” + Sair) e se o menu permanece após o carregamento completo da página.
3. **Mapa:** com usuário logado, conferir que não aparece mais “Faça login para reservar” acima da tabela.
4. **Logout:** clicar em “Sair” e conferir que o header volta a mostrar só “Entrar”.
5. **Links:** conferir que “Entrar” e “Cadastre-se” levam às rotas `index.php?c=auth&a=login` e `?c=auth&a=register`.

---

## Passo 12 – Commit da Aula 3

```bash
git add .
git status
git commit -m "Aula 3: Autenticação e cadastro (UsuarioModel, AuthController, login, register, logout, sessão, header condicional, body user-logado, ajuste app.js)"
```

---

## Resumo do que foi feito

| Item | Onde / O quê |
|------|----------------|
| Sessão | `index.php` – `session_start()` após o config |
| Conexão | `index.php` – `require_once CONFIG_PATH . '/conexao.php'` antes dos controllers |
| UsuarioModel | `app/Models/UsuarioModel.php` – `buscarPorEmail()`, `criar()` com prepared statement |
| AuthController | `app/Controllers/AuthController.php` – `login()`, `logout()`, `register()` |
| Views de auth | `app/Views/auth/login.php`, `register.php` – formulários POST e mensagens da sessão |
| Header | `includes/header.php` – rotas MVC, menu quando logado, body com classe `user-logado` |
| app.js | Não remover a classe `user-logado` do body (menu controlado pelo PHP) |
| Pasta auth | Só `app/Views/auth/` (login e register). A pasta `app/auth/` foi removida. |
| Mapa | `app/Views/mapa.php` – “Faça login” só para visitante |
| Rotas | `?c=auth&a=login`, `?c=auth&a=logout`, `?c=auth&a=register` |

Na **Aula 4** você usará `$_SESSION['usuario_id']` para **criar uma reserva**: formulário (sala, data, horário), validação e INSERT na tabela `reservas`.
