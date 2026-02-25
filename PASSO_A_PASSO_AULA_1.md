# Aula 1 – Banco de dados, estrutura MVC e conexão PDO

Passo a passo para acompanhar e commitar a Aula 1.

---

## Ordem do que fazer primeiro

Siga nesta ordem para não travar em nada:

| # | O que fazer | O que você ajusta |
|---|-------------|-------------------|
| 1 | [Passo 1](#passo-1--criar-a-branch) | Criar a branch (se usar Git). |
| 2 | [Passo 2](#passo-2--executar-o-script-do-banco) | **Executar o `schema.sql`** no MySQL — sem isso o banco não existe. |
| 3 | [Passo 3](#passo-3--ajustar-a-conexão-se-precisar) | **Revisar `config/conexao.php`** — senha do MySQL, nome do banco, usuário. |
| 4 | [Passo 4](#passo-4--testar-a-conexão-pdo-opcional) | (Opcional) Criar e acessar `testar_conexao.php` para validar o PDO. |
| 5 | [Passo 5](#passo-5--entender-a-estrutura-de-pastas-mvc) | Só leitura — entender onde ficam Controllers, Views, config. |
| 6 | [Passo 6](#passo-6--fluxo-da-requisição-front-controller) | Só leitura — entender a rota do mapa (`c=mapa`, `a=index`). |
| 7 | [Passo 7](#passo-7--testar-no-navegador) | **Abrir a URL** e conferir se o mapa aparece. |
| 8 | [Passo 8](#passo-8--fazer-o-commit-da-aula-1) | **Commit** (se usar Git). |

Resumindo: o que você **precisa fazer** é (2) rodar o script do banco, (3) conferir a conexão e (7) testar no navegador. O resto do código (MVC, autoload, Front Controller) já vem pronto.

---

## O que a Aula 1 entrega

- Banco de dados criado (tabelas `usuarios`, `salas`, `reservas`)
- Estrutura de pastas no padrão MVC
- Configuração centralizada e conexão PDO
- Autoload para carregar classes
- Front Controller: uma única entrada (`index.php`) que encaminha para os controllers

---

## Passo 1 – Criar a branch

```bash
git checkout -b aula-1
```

*(Se já estiver na branch `aula-1`, pode pular.)*

---

## Passo 2 – Executar o script do banco

**O que você ajusta:** executar o script (phpMyAdmin ou terminal). Sem isso o banco e as tabelas não existem.

1. Abra o **phpMyAdmin** (ou outro cliente MySQL): `http://localhost/phpmyadmin`
2. Vá em **Importar** (ou **SQL**) e execute o arquivo:
   - `database/schema.sql`
3. Ou, no terminal MySQL:

```bash
mysql -u root -p < database/schema.sql
```

4. Confira se o banco `reserva_salas` foi criado e se as tabelas `usuarios`, `salas` e `reservas` existem.
5. A tabela `salas` já vem com 6 registros de exemplo.

---

## Passo 3 – Ajustar a conexão (se precisar)

**O que você ajusta:** em `config/conexao.php`, conferir/alterar `DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS` (principalmente senha do MySQL).

- **`config/config.php`** – só caminhos de pasta: `BASE_PATH` e `APP_PATH`. Não precisa mudar.
- **`config/conexao.php`** – padrão de conexão PDO: constantes do banco (`DB_HOST`, `DB_NAME`, `DB_USER`, `DB_PASS`) e a criação do `$pdo`. Ajuste aqui se no seu ambiente o MySQL tiver outra senha ou outro banco.

---

## Passo 4 – Testar a conexão PDO (opcional)

**O que você ajusta:** criar o arquivo `testar_conexao.php` na raiz, acessar no navegador e, depois, apagar o arquivo.

Crie um arquivo temporário **`testar_conexao.php`** na raiz:

```php
<?php
require_once __DIR__ . '/config/conexao.php';

try {
    $pdo->query('SELECT 1');
    echo 'Conexão com o banco OK.';
} catch (Exception $e) {
    echo 'Erro: ' . $e->getMessage();
}
```

Acesse: `http://localhost/reserva-de-salas/testar_conexao.php`  
*(Depois você pode apagar esse arquivo.)*

---

## Passo 5 – Entender a estrutura de pastas (MVC)

**O que você ajusta:** nada — só ler e localizar onde ficam Controllers, Views, config e `schema.sql`.

```
reserva-de-salas/
├── app/
│   ├── Controllers/     ← Controllers (ex.: MapaController)
│   ├   └── MapaController.php
│   ├── Models/          ← Models (Aula 2)
│   ├── Views/           ← Views (conteúdo das páginas)
│   │   └── mapa.php
│   └── autoload.php     ← Carrega as classes automaticamente
├── config/
│   ├── config.php       ← Só caminhos de pasta (BASE_PATH, APP_PATH)
│   └── conexao.php      ← Padrão de conexão PDO ($pdo)
├── database/
│   └── schema.sql       ← Script para criar o banco e tabelas
├── index.php            ← Front Controller (entrada única)
├── includes/            ← Header e footer (como já existiam)
├── assets/
└── ...
```

---

## Passo 6 – Fluxo da requisição (Front Controller)

**O que você ajusta:** nada — só entender o fluxo URL → index.php → MapaController → mapa.php.

1. O usuário acessa: `http://localhost/reserva-de-salas/` ou `index.php?c=mapa&a=index`
2. O **index.php** (Front Controller):
   - Carrega `config.php` e `autoload.php`
   - Lê `c` (controller) e `a` (action) da URL; se não vier nada, usa `mapa` e `index`
   - Monta o nome da classe: `MapaController`
   - Instancia `MapaController` e chama o método `index()`
3. O **MapaController::index()**:
   - Inclui o header
   - Inclui a view `app/Views/mapa.php`
   - Inclui o footer

Assim, a página do mapa passa a ser exibida via MVC.

---

## Passo 7 – Testar no navegador

**O que você ajusta:** abrir a URL e conferir se a tela do mapa aparece (sem erro de conexão ou 404).

- **Página inicial (mapa):**  
  `http://localhost/reserva-de-salas/`  
  ou  
  `http://localhost/reserva-de-salas/index.php`  
  ou  
  `http://localhost/reserva-de-salas/index.php?c=mapa&a=index`

A tela do mapa de salas deve aparecer igual à que você já tinha (por enquanto ainda com dados fixos na view).

---

## Passo 8 – Fazer o commit da Aula 1

**O que você ajusta:** rodar os comandos Git (se usar repositório).

```bash
git add .
git status
git commit -m "Aula 1: Banco de dados, estrutura MVC, config, PDO e Front Controller"
```

---

## Resumo do que foi feito

| Item              | Onde / O quê |
|-------------------|--------------|
| Script do banco   | `database/schema.sql` – cria `reserva_salas`, tabelas e dados iniciais de salas |
| Config            | `config/config.php` – caminhos; `config/conexao.php` – conexão PDO (`$pdo`) |
| MVC               | `app/Controllers/`, `app/Models/`, `app/Views/` |
| Autoload          | `app/autoload.php` – carrega Controllers e Models pelo nome da classe |
| Front Controller  | `index.php` – lê `c` e `a`, instancia o controller e chama a action |
| Primeira rota     | `?c=mapa&a=index` → `MapaController::index()` → view `mapa.php` |

Na **Aula 2** você vai criar os **Models** (Sala, Reserva), usar a conexão PDO para buscar dados no banco e exibir as salas (e depois as reservas) na tela.
