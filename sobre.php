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
    <title>FinWise</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="sobre.css">
    <link rel="icon" href="Mídias/Logo-branca.ico">
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
    <main>
        <section class="sobre-apresentacao">
            <h2>Nossa História e Propósito</h2>
            <p>A NeoWise é uma plataforma de cursos online criada para transformar a forma como você lida com o crescimento pessoal, programas e decisões financeiras. Com conteúdos práticos, acessíveis e desenvolvidos por especialistas, oferecemos uma jornada de aprendizado completa para quem busca mais controle financeiro, crescimento profissional e autonomia.</p>
        </section>

        <section class="mvv-container">
            <div class="mvv-card">
                <h3>Missão</h3>
                <p>Capacitar milhões de pessoas a alcançar a liberdade financeira através de educação prática, ética e acessível, desmistificando o mundo das finanças e investimentos.</p>
            </div>
            <div class="mvv-card">
                <h3>Visão</h3>
                <p>Ser reconhecida como a plataforma líder em educação financeira e empreendedorismo digital na América Latina, sendo referência em qualidade e impacto social.</p>
            </div>
            <div class="mvv-card">
                <h3>Valores</h3>
                <ul>
                    <li>Transparência e Ética</li>
                    <li>Foco no Resultado do Aluno</li>
                    <li>Inovação Contínua</li>
                    <li>Acessibilidade</li>
                </ul>
            </div>
        </section>

        <section class="cta-banner">
            <h2>Comece Sua Jornada Hoje!</h2>
            <p>Mais de 50 mil alunos já transformaram suas vidas com nossos cursos. Junte-se a eles.</p>
            <a href="cursos.php" class="btn-cta">Ver Todos os Cursos</a>
        </section>
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
</body>
</html>