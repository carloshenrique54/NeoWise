<!DOCTYPE html>
<html lang="pt-BR">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Criar Conta - Cadastro</title>
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="cadastro.css">
  <link rel="icon" href="Mídias/icone.ico">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
</head>
<body>
  <header>
    <div id="logo">
        <img src="Mídias/Logo branca.png" alt="Logo da FinWise" />
    </div>
    <nav>
        <ul>
            <li><a id="home" href="index.php">Ínicio</a></li>
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
  <form id="register_form" action="cadastramentos.php" method="POST" onsubmit="return validarForms(event)">
    <h1>Criar Conta</h1>

    <label for="username">Nome completo:</label>
    <input type="text" id="nome" name="nome" required>

    <label for="email">Email:</label>
    <input type="email" id="email" name="email" required>

    <label for="telefone">Telefone:</label>
    <input type="text" id="telefone" name="telefone" required maxlength="15">

    <label for="cpf">CPF:</label>
    <input type="text" id="cpf" name="cpf" required maxlength="14">

    <label for="senha">Senha:</label>
    <input type="password" id="senha" name="senha" required>

    <label for="confirmar_senha">Confirmar Senha:</label>
    <input type="password" id="confirmar_senha" name="confirmar_senha" required>

    <p id="erro-senha" style="color: red; font-weight: bold;"></p>

    <button id="register" type="submit">Criar Conta</button>
    <p>Já tem uma conta? <a href="login.php">Faça login</a></p>
  </form>
  <div id="mensagem"></div>
  <img src="Mídias/login-imagem.png" alt="Login">
  </main>
  <footer>
        <div id="footer-content">
            <div class="footer-section">
                <h3>NeoWise</h3>
                <p>Sua plataforma de cursos online para se tornar um profissional de destaque no mercado.</p>
            </div>
            <div class="footer-section">
                <h3>Links Rápidos</h3>
                <ul>
                    <li><a href="index.php">Ínicio</a></li>
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
    <script src="cadastro.js"></script>
    <div id="toast-erro" class="toast-container">
    
        <i id="toast-icon" class="fa-regular fa-circle-xmark"></i>
      
      <p id="toast-mensagem">Esta é uma mensagem de erro padrão.</p>
    
    </div>
</body>
</html>

