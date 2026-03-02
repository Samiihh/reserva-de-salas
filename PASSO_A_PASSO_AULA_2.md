# Aula 2 – Mapa de salas, Minhas Reservas e criar reserva

Passo a passo da Aula 2: buscar salas e reservas no banco (Models e Controllers), exibir no mapa e em “Minhas Reservas”, proteger rotas e implementar o fluxo completo de **nova reserva** (formulário POST, validação, conflito de horários).

---

## Ordem do que fazer

| # | O que fazer | Onde |
|---|-------------|------|
| 1 | Criar a branch `aula-2` | Git |
| 2 | Criar **SalaModel** (listar salas do banco) | `app/Models/SalaModel.php` |
| 3 | **MapaController** buscar salas no Model e preparar badge | `app/Controllers/MapaController.php` |
| 4 | View do mapa usar `$salas` e variável `$logado` para o link “Faça login” | `app/Views/mapa.php` |
| 5 | Criar **ReservaModel** (listar todas, listar por usuário, verificar conflito, criar) | `app/Models/ReservaModel.php` |
| 6 | Criar **MinhasReservasController** (exigir login, listar reservas do usuário, carregar salas para o modal) | `app/Controllers/MinhasReservasController.php` |
| 7 | View Minhas Reservas (tabela, mensagens de sucesso/erro, cards de estatísticas) | `app/Views/minhasreservas.php` |
| 8 | Criar **helpers** (urlIndex, exigirLogin) e carregar no index | `app/helpers.php`, `index.php` |
| 9 | Criar **ReservaController** (action criar: POST validar, conflito, INSERT) | `app/Controllers/ReservaController.php` |
| 10 | Modal Nova Reserva: form POST para criar + select de salas do banco | `includes/modal-nova-reserva.php` |
| 11 | Não interceptar o submit do form no JS (deixar POST normal) | `assets/js/app.js` |
| 12 | Testar mapa, minhas reservas, nova reserva e conflito de horário | Navegador |
| 13 | Commit da Aula 2 | Git |

---

## O que a Aula 2 entrega

- **SalaModel**: `listarTodas()` — salas vindas do banco para o mapa.
- **MapaController**: busca salas no Model, prepara badge (texto/classe) e inclui a view do mapa.
- **ReservaModel**: `listarTodas()`, `listarPorUsuario($usuario_id)`, `salaDisponivel()` (evita conflito de horário), `criar()` (INSERT da reserva).
- **MinhasReservasController**: rota protegida com `exigirLogin()`; lista apenas reservas do usuário; carrega `$salas` para o modal.
- **ReservaController**: action `criar` — GET redireciona; POST valida (sala, data, horários), verifica conflito e grava a reserva.
- **Helpers**: `urlIndex()` e `exigirLogin()` (carregados pelo `index.php`).
- **Modal Nova Reserva**: formulário com `action` e `method="post"` para `?c=reserva&a=criar`; select de salas preenchido com dados do banco (`$salas`).
- **View Minhas Reservas**: exibe mensagens de sucesso/erro da sessão (após criar reserva) e lista apenas as reservas do usuário logado.

Com a **Aula 3** (autenticação) já feita, o usuário logado consegue ver o mapa, abrir “Minhas Reservas”, criar uma nova reserva pelo modal e ver a lista atualizada, com validação de conflito de horário.

---

## Passo 1 – Criar a branch

```bash
git checkout -b aula-2
```

*(Se já estiver na branch `aula-2`, pode pular.)*

---

## Passo 2 – SalaModel

Criar `app/Models/SalaModel.php`:

- **Construtor:** guardar a conexão PDO no objeto (`global $pdo` → `$this->pdo`). Opcional: validar se `$pdo` existe.
- **listarTodas():** `SELECT id, nome, capacidade, andar, recursos, status FROM salas ORDER BY nome ASC`; retornar array de arrays (fetchAll PDO::FETCH_ASSOC).

A view do mapa e o modal “Nova Reserva” usam essa lista (o controller passa `$salas` para o header/view).

---

## Passo 3 – MapaController

No `app/Controllers/MapaController.php` (ou criar se ainda não existir):

- No método **index()**:
  1. Instanciar `SalaModel` e chamar `listarTodas()` → `$salas`.
  2. Para cada sala, adicionar `badge_texto` e `badge_classe` conforme o `status` (disponivel, em_uso, manutencao).
  3. Incluir o header (que pode incluir o modal), a view `app/Views/mapa.php` e o footer.
- A view do mapa recebe `$salas` já com os badges; ela só exibe (e usa `$logado` para o texto “Faça login para reservar”, definido na própria view a partir de `$_SESSION['usuario_id']`).

---

## Passo 4 – View do mapa

Em `app/Views/mapa.php`:

- Garantir que existe `$salas` (ex.: `if (!isset($salas)) $salas = [];`).
- Definir `$logado = isset($_SESSION['usuario_id']);` para exibir ou não o link “Faça login para reservar”.
- Exibir a tabela de salas em um loop sobre `$salas` (nome, capacidade, andar, recursos, badge). Usar `htmlspecialchars()` nos textos para evitar XSS.

---

## Passo 5 – ReservaModel

Criar ou completar `app/Models/ReservaModel.php`:

- **Construtor:** guardar `$pdo` no objeto.
- **listarTodas():** `SELECT` das reservas com `INNER JOIN salas` para trazer `sala_nome`; ordenar por data e hora (DESC). Retornar array de arrays.
- **listarPorUsuario(int $usuario_id):** mesmo SELECT, com `WHERE r.usuario_id = :usuario_id`, para a página “Minhas Reservas”.
- **salaDisponivel($sala_id, $data_reserva, $hora_inicio, $hora_fim, $excluir_reserva_id = null):** contar reservas da mesma sala na mesma data com sobreposição de horário (status pendente ou confirmada). Retornar `true` se count = 0 (sala livre).
- **criar($usuario_id, $sala_id, $data_reserva, $hora_inicio, $hora_fim, $observacoes):** `INSERT INTO reservas (...)` com prepared statement.

---

## Passo 6 – MinhasReservasController

No `app/Controllers/MinhasReservasController.php`:

- No início do método **index()**: chamar **exigirLogin()** (rota protegida).
- Buscar reservas com `ReservaModel::listarPorUsuario((int) $_SESSION['usuario_id'])` (não usar `listarTodas()`).
- Carregar as salas com `SalaModel::listarTodas()` → `$salas`, para o modal (incluído no header) ter o select preenchido.
- Para cada reserva, adicionar `badge_texto`, `badge_classe`, `data_formatada` (dd/mm/aaaa) e `horario_formatado` (HH:MM – HH:MM).
- Incluir header, view `app/Views/minhasreservas.php` e footer.

---

## Passo 7 – View Minhas Reservas

Em `app/Views/minhasreservas.php`:

- Garantir `$reservas` (ex.: `if (!isset($reservas)) $reservas = [];`).
- Ler da sessão `erro_reserva` e `sucesso_reserva` (mensagens após criar reserva); exibir em blocos com classe de erro/sucesso; dar `unset` após exibir para não reaparecer ao recarregar.
- Exibir os cards de estatísticas (total de reservas, salas ativas com `count($salas)`, próxima reserva).
- Tabela com colunas: Sala, Data, Horário, Status (badge), link “Detalhes” (pode ficar como `#` por enquanto). Usar `htmlspecialchars()` nos dados.

---

## Passo 8 – Helpers e index.php

- Criar **`app/helpers.php`** com:
  - **urlIndex():** retorna a URL base do `index.php` (respeitando BASE_URL para raiz ou subpasta).
  - **exigirLogin():** se `!isset($_SESSION['usuario_id'])`, envia `header('Location: ' . urlIndex() . '?c=auth&a=login')` e `exit`.
- No **`index.php`**, após o autoload, dar `require_once APP_PATH . '/helpers.php'` para que os controllers possam usar essas funções.

---

## Passo 9 – ReservaController

Criar `app/Controllers/ReservaController.php`:

- **criar():**
  - Se for **GET:** redirecionar para `?c=minhas-reservas`.
  - Se for **POST:**
    1. Chamar **exigirLogin()**.
    2. Pegar e validar: sala (id), data, hora_inicio, hora_fim, observacoes (opcional).
    3. Validar: data não no passado; hora_fim > hora_inicio.
    4. Usar **ReservaModel::salaDisponivel()**; se não estiver livre, gravar `$_SESSION['erro_reserva']` e redirecionar para minhas-reservas.
    5. Chamar **ReservaModel::criar()** com o `usuario_id` da sessão; em sucesso, gravar `$_SESSION['sucesso_reserva']` e redirecionar para minhas-reservas; em exceção PDO, gravar mensagem de erro e redirecionar.

Rota: `?c=reserva&a=criar` (o formulário do modal envia POST para essa URL).

---

## Passo 10 – Modal Nova Reserva

Em `includes/modal-nova-reserva.php`:

- Definir a URL de criação: `$urlCriarReserva = (BASE_URL === '' ? '' : BASE_URL . '/') . 'index.php?c=reserva&a=criar'`.
- No `<form>`, usar `action="<?= htmlspecialchars($urlCriarReserva) ?>"` e `method="post"`.
- Campos: `name="sala"`, `name="data"`, `name="hora_inicio"`, `name="hora_fim"`, `name="observacoes"`.
- Select de salas: em vez de options fixas, usar `$salas` (ou `isset($salas) ? $salas : []`) e gerar `<option value="<?= $sala['id'] ?>"><?= htmlspecialchars($sala['nome']) ?></option>` para cada sala. Manter a opção vazia “Selecione a sala”.
- O header é incluído pelas páginas que já definem `$salas` (MapaController e MinhasReservasController), então o modal terá a lista de salas disponível.
- Script do `app.js`: usar `ASSETS_URL` para o `src` (ex.: `<?= ASSETS_URL ?>/js/app.js`).

---

## Passo 11 – JavaScript (app.js)

No `assets/js/app.js`, no trecho do formulário “Nova Reserva”:

- **Não** fazer `preventDefault()` no evento `submit` do form. O formulário deve ser enviado normalmente (POST) para `?c=reserva&a=criar`, a página recarrega e exibe a mensagem de sucesso ou erro em Minhas Reservas.
- Manter a lógica de abrir/fechar o modal (clique nos botões, overlay, tecla Escape). Pode deixar um comentário explicando que o submit é intencionalmente não interceptado.

---

## Passo 12 – Testar no navegador

1. **Mapa:** acessar a página inicial e conferir se as salas vêm do banco (nomes, capacidade, andar, recursos, status com badge).
2. **Login:** fazer login (Aula 3) e acessar “Minhas Reservas”. Deve redirecionar para login se não estiver logado.
3. **Minhas Reservas:** conferir que só aparecem as reservas do usuário logado; conferir mensagens de sucesso/erro após criar reserva.
4. **Nova Reserva:** abrir o modal, selecionar sala (lista do banco), data e horários, enviar. Conferir redirect e mensagem “Reserva realizada com sucesso.” e a nova linha na tabela.
5. **Conflito:** tentar criar outra reserva na mesma sala, mesma data e horário sobreposto; deve exibir mensagem de erro (sala já reservada).
6. **Validação:** enviar com data no passado ou com horário fim antes do início; deve exibir mensagem de erro.

---

## Passo 13 – Commit da Aula 2

```bash
git add .
git status
git commit -m "Aula 2: Mapa e Minhas Reservas com dados do banco; criar reserva (ReservaController, modal POST, conflito de horários, proteção de rotas)"
```

---

## Resumo do que foi feito

| Item | Onde / O quê |
|------|----------------|
| SalaModel | `app/Models/SalaModel.php` – `listarTodas()` |
| MapaController | `app/Controllers/MapaController.php` – busca salas, prepara badge, inclui view mapa |
| View mapa | `app/Views/mapa.php` – tabela com `$salas`, variável `$logado` para “Faça login” |
| ReservaModel | `app/Models/ReservaModel.php` – `listarTodas()`, `listarPorUsuario()`, `salaDisponivel()`, `criar()` |
| MinhasReservasController | `app/Controllers/MinhasReservasController.php` – `exigirLogin()`, listar por usuário, passar `$salas` |
| View minhas reservas | `app/Views/minhasreservas.php` – tabela, mensagens sessão, cards |
| Helpers | `app/helpers.php` – `urlIndex()`, `exigirLogin()`; carregado em `index.php` |
| ReservaController | `app/Controllers/ReservaController.php` – `criar()` (GET redirect, POST validar + conflito + INSERT) |
| Modal Nova Reserva | `includes/modal-nova-reserva.php` – form POST para `?c=reserva&a=criar`, select com `$salas` |
| app.js | Formulário não intercepta submit (POST normal) |
| Rotas | `?c=mapa`, `?c=minhas-reservas`, `?c=reserva&a=criar` |

Com isso, o sistema de reserva de salas fica completo: mapa com salas do banco, minhas reservas por usuário e criação de reserva com validação e verificação de conflito de horário.
