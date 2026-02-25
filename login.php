<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <title>Login - Sistema de Reserva de Salas</title>

    <!-- CAMINHO ABSOLUTO COM NOME DO PROJETO -->
    <link rel="stylesheet" href="/reserva-de-salas/assets/css/base.css">
    <link rel="stylesheet" href="/reserva-de-salas/assets/css/auth.css">
</head>

<body class="auth-body">

    <div class="auth-card">
        <h1>Sistema de Reserva de Salas</h1>
        <h2>Entrar</h2>

        <form id="form-login">
            <div class="form-group">
                <label>Email</label>
                <input type="email" placeholder="Digite seu email" required>
            </div>

            <div class="form-group">
                <label>Senha</label>
                <input type="password" placeholder="Digite sua senha" required>
            </div>

            <button type="submit" class="btn-primary">
                Entrar
            </button>
        </form>

        <p class="auth-link">
            Não tem conta?
            <a href="register.php">Cadastre-se</a>
        </p>
    </div>

    <script>
      (function () {
        sessionStorage.removeItem('logado');
        document.getElementById('form-login').addEventListener('submit', function (e) {
          e.preventDefault();
          sessionStorage.setItem('logado', '1');
          window.location.href = 'mapa.php';
        });
      })();
    </script>
</body>

</html>