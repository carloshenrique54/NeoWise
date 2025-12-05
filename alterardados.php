<?php
require('conexao.php'); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$foto_perfil_url = $_SESSION['usuario']['foto'] ?? 'Mídias/perfil.png'; 
$aluno_cpf = null;

if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['cpf'])) {
    $aluno_cpf = $_SESSION['usuario']['cpf'];

    if (isset($_SESSION['usuario']['foto']) && !empty($_SESSION['usuario']['foto'])) {
        $foto_perfil_url = htmlspecialchars($_SESSION['usuario']['foto']);
    }

    if (isset($conn) && $conn) {
        $sql_busca = "SELECT Foto FROM alunos WHERE CPF = ?"; 
        
        if ($stmt_busca = $conn->prepare($sql_busca)) {
            $stmt_busca->bind_param("s", $aluno_cpf);
            $stmt_busca->execute();
            $stmt_busca->bind_result($foto_caminho_db);

            if ($stmt_busca->fetch() && !empty($foto_caminho_db)) {
                $foto_perfil_url = htmlspecialchars($foto_caminho_db);
                
                $_SESSION['usuario']['foto'] = $foto_caminho_db; 
            }
            $stmt_busca->close();
        }
    } 
} 
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise</title>
    <link rel="stylesheet" href="alterardados.css">
    <link rel="stylesheet" href="header.css">
</head>
<body>
<header>
    <div id="logo">
        <img src="Mídias/Logo branca.png" alt=" Logo da FinWise" />
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
    <form method="POST" action="mudardados.php">
        <h1>Mude seus dados</h1>
        <label for="password">Confime sua senha</label>
        <input type="password" name="senha" placeholder="Digite sua senha" required>
        <button type="submit">Alterar dados</button>
    </form>
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
<script src="tema.js"></script>
</body>
</html>
