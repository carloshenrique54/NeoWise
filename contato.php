<?php
require('conexao.php');

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="contato.css">
    <link rel="icon" href="Mídias/icone.ico">
</head>
<body>
<header>
    <div id="logo">
        <img src="Mídias/Logo branca.png" alt="Logo da FinWise" />
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
                $foto_perfil_url = $_SESSION['usuario']['foto'] ?? 'Mídias/perfil.png'; 

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
    <div id="mensagem-status" class="mensagem-oculta"></div>
    <main>
        <h2>Preencha o Formulário</h2>
        <form id="form-contato" method="post" action="enviar_mensagem.php">
            <div class="info">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="nome" required>
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" required>
                <label for="assunto">Assunto:</label>
                <input type="text" id="assunto" name="assunto" required>
            </div>
            <div class="info">
                <label for="mensagem">Mensagem:</label>
                <textarea id="mensagem" name="mensagem" required></textarea>
                <button type="submit">Enviar</button>
            </div>
        </form>
        <div id="faq">
            <h2>Dúvidas frequentes</h2>
            <div class="faq-item">
                <h3>Como me inscrevo em um curso?</h3>
                <p>Para se inscrever em um curso, basta clicar no curso desejado na página de cursos e seguir as instruções de inscrição.</p>
            </div>
            <div class="faq-item">
                <h3>Quais são os métodos de pagamento aceitos?</h3>
                <p>A FinWise aceita pagamentos via cartão de crédito, boleto bancário e PayPal.</p>
            </div>
            <div class="faq-item">
                <h3>Posso acessar os cursos offline?</h3>
                <p>Sim, você pode baixar o material do curso para acesso offline através do nosso aplicativo móvel.</p>
            </div>
            <div class="faq-item">
                <h3>Como entro em contato com o suporte?</h3>
                <p>Você pode entrar em contato com o suporte através do formulário de contato nesta página ou enviando um email para nós</p>
            </div>
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
    <script src="tema.js"></script>
    <script src="contato.js"></script>
</body>
</html>