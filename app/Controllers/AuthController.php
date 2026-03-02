<?php

/**
 * AuthController - Aula 3
 *
 * Controlador responsável por autenticação de usuários:
 * - Login: exibe formulário (GET) e processa credenciais (POST)
 * - Logout: encerra a sessão e redireciona
 * - Register: exibe formulário de cadastro (GET) e cria novo usuário (POST)
 *
 * Utiliza UsuarioModel para buscar e criar usuários no banco.
 * Mantém o usuário logado via $_SESSION (usuario_id, usuario_nome).
 *
 * Rotas utilizadas:
 *   ?c=auth&a=login   - Página de login
 *   ?c=auth&a=logout  - Encerrar sessão
 *   ?c=auth&a=register - Página de cadastro
 */

class AuthController
{
    /**
     * Monta a URL base do index.php para redirecionamentos.
     * Funciona tanto na raiz (ex.: localhost) quanto em subpasta (ex.: /reserva-de-salas).
     *
     * @return string URL completa do index.php (ex.: /reserva-de-salas/index.php)
     */
    private function urlIndex(): string
    {
        return (BASE_URL === '' ? '' : BASE_URL . '/') . 'index.php';
    }

    /**
     * Login: GET exibe o formulário; POST valida credenciais e grava sessão.
     * Em caso de sucesso, redireciona para o mapa (?c=mapa).
     */
    public function login(): void
    {
        // --- Requisição POST: processar envio do formulário ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';

            // Validação: campos obrigatórios
            if ($email === '' || $senha === '') {
                $_SESSION['erro_login'] = 'Preencha email e senha.';
                header('Location: ' . $this->urlIndex() . '?c=auth&a=login');
                exit;
            }

            // Busca usuário pelo email no banco
            $model = new UsuarioModel();
            $usuario = $model->buscarPorEmail($email);

            // password_verify compara a senha digitada com o hash guardado no banco
            if (!$usuario || !password_verify($senha, $usuario['senha'])) {
                $_SESSION['erro_login'] = 'Email ou senha incorretos.';
                header('Location: ' . $this->urlIndex() . '?c=auth&a=login');
                exit;
            }

            // Login OK: grava dados na sessão e redireciona para o mapa
            $_SESSION['usuario_id'] = (int) $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            unset($_SESSION['erro_login']);
            header('Location: ' . $this->urlIndex() . '?c=mapa');
            exit;
        }

        // --- Requisição GET: exibir formulário de login ---
        require APP_PATH . '/Views/auth/login.php';
    }

    /**
     * Logout: limpa a sessão, invalida o cookie de sessão e redireciona para o mapa.
     * Garante que o usuário não permaneça logado após clicar em "Sair".
     */
    public function logout(): void
    {
        $_SESSION = [];

        // Remove o cookie de sessão do navegador (segurança)
        if (ini_get('session.use_cookies')) {
            $p = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000, $p['path'], $p['domain'], $p['secure'], $p['httponly']);
        }

        session_destroy();
        header('Location: ' . $this->urlIndex() . '?c=mapa');
        exit;
    }

    /**
     * Cadastro: GET exibe o formulário; POST valida dados, cria usuário (senha hasheada) e redireciona para login.
     * Senha é armazenada com password_hash (PASSWORD_DEFAULT); email deve ser único na tabela.
     */
    public function register(): void
    {
        // --- Requisição POST: processar cadastro ---
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nome = trim($_POST['nome'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $senha = $_POST['senha'] ?? '';
            $confirmar = $_POST['confirmar_senha'] ?? '';

            // Validação: todos os campos obrigatórios
            if ($nome === '' || $email === '' || $senha === '' || $confirmar === '') {
                $_SESSION['erro_cadastro'] = 'Preencha todos os campos.';
                header('Location: ' . $this->urlIndex() . '?c=auth&a=register');
                exit;
            }

            // Validação: confirmação de senha
            if ($senha !== $confirmar) {
                $_SESSION['erro_cadastro'] = 'As senhas não coincidem.';
                header('Location: ' . $this->urlIndex() . '?c=auth&a=register');
                exit;
            }

            // Validação: tamanho mínimo da senha
            if (strlen($senha) < 6) {
                $_SESSION['erro_cadastro'] = 'A senha deve ter no mínimo 6 caracteres.';
                header('Location: ' . $this->urlIndex() . '?c=auth&a=register');
                exit;
            }

            $model = new UsuarioModel();
            try {
                // password_hash gera um hash seguro para guardar no banco (nunca guardar senha em texto puro)
                $model->criar($nome, $email, password_hash($senha, PASSWORD_DEFAULT));
                unset($_SESSION['erro_cadastro']);
                $_SESSION['sucesso_cadastro'] = 'Conta criada. Faça login.';
                header('Location: ' . $this->urlIndex() . '?c=auth&a=login');
                exit;
            } catch (PDOException $e) {
                $msg = $e->getMessage();
                // Código 1062 ou "Duplicate" = email já cadastrado (constraint UNIQUE na tabela)
                $_SESSION['erro_cadastro'] = (stripos($msg, 'Duplicate') !== false || stripos($msg, '1062') !== false)
                    ? 'Este email já está em uso.'
                    : 'Erro ao cadastrar: ' . $msg;
                header('Location: ' . $this->urlIndex() . '?c=auth&a=register');
                exit;
            } catch (Throwable $e) {
                $_SESSION['erro_cadastro'] = 'Erro ao cadastrar: ' . $e->getMessage();
                header('Location: ' . $this->urlIndex() . '?c=auth&a=register');
                exit;
            }
        }

        // --- Requisição GET: exibir formulário de cadastro ---
        require APP_PATH . '/Views/auth/register.php';
    }
}
