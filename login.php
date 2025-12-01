<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Acessar Conta - Login</title>
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="login.css">
  <link rel="icon" href="Mídias/icone.ico">
</head>
<body>
  <header>
    <div id="logo">
        <img src="Mídias/Logo branca.png" alt="Logo da FinWise"/>
    </div>
    <nav>
        <ul>
            <li><a id="home" href="index.php">Home</a></li>
            <li><a id="cursos" href="cursos.php">Cursos</a></li>
            <li><a id="sobre" href="sobre.php">Sobre</a></li>
            <li><a id="contato" href="contato.php">Contato</a></li>
            <li><a id="forum" href="forum.php">Fórum</a></li>
        </ul>
        <ul>
            <?php

            if (!isset($_SESSION['usuario'])) {
               
                echo '<li><a href="login.php">Login</a></li>';
                echo '<li><a id="cadastro" href="cadastro.php">Cadastre-se</a></li>';
            } else {

                $nome_completo = $_SESSION['usuario']['nome'];
                $partes_nome = explode(' ', $nome_completo);
                $primeiro_nome = $partes_nome[0];

                if ($_SESSION['usuario']['acesso'] == 0) {
                    echo '<a href="perfil.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                } else {
                    echo '<a href="perfiladm.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                }

                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>
    <main>
      <form id="login_form" action="loging.php" method="POST" class="form-box">
        <h1>Acessar Conta</h1>
        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="senha">Senha:</label>
        <input type="password" id="senha" name="senha" required>

        <div id="actions">
            <button type="submit">Fazer login</button>
        </div>
        <button type="button" id="forgot-password">Esqueci minha senha</button>
        <p>Não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        
            <span id="mensagem"></span>

      </form>
      <div id="mensagem"></div>
      <img src="Mídias/login-imagem.png" alt="Login">
    </main>
    <footer>
        <div id="footer-content">
            <div class="footer-section">
                <h3>FinWise</h3>
                <p>Sua plataforma de cursos online para se tornar um profissional de destaque no mercado.</p>
            </div>
            <div class="footer-section">
                <h3>Links Rápidos</h3>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="cursos.php">Cursos</a></li>
                    <li><a href="sobre.php">Sobre</a></li>
                    <li><a href="contato.php">Contato</a></li>
                </ul>
            </div>
            <div class="footer-section">
                <h3>Contato</h3>
                <p>Email: contato@neowise.com.br</p>
                <p>Telefone: (11) 1234-5678</p>
                <p>Instagram: @neowise</p>
            </div>
        </div>
    </footer>
    <div class="modal-overlay" id="modal-reset">
    <div class="modal-box">
        <form id="reset-form">
            <button type="button" id="close-modal">Voltar</button>
            <h1 id="recuperar-senha">Recuperar Senha</h1>

            <label id="label-email" for="email">Insira seu e-mail cadastrado</label>
            <input type="email" id="email-modal" placeholder="Digite seu e-mail" required>

            <button type="submit">Recuperar Senha</button>
        </form>
    </div>
</div>

<div id="toast" class="toast">Email enviado</div>

<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>

    <script>
    (function(){
        emailjs.init({
            publicKey: "yexqvxDUHrrXPU6Wf",
        });
    })();
    </script>

    <script src="esqueci_senha.js"></script>
    <script src="login.js"></script>
</body>
</html>
