<?php
session_start();
require 'conexao.php';

function getUserHashFromSession($conn) {
    if (empty($_SESSION['usuario'])) {
        return null;
    }

    $usuario = $_SESSION['usuario'];
    $cpf = $usuario['cpf'] ?? '';

    if (empty($cpf)) {
        return null;
    }

    $sql = "SELECT senhahash FROM alunos WHERE cpf = ? LIMIT 1";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param('s', $cpf);
        $stmt->execute();
        $res = $stmt->get_result();
        $row = $res->fetch_assoc();
        $stmt->close();
        return $row['senhahash'] ?? null;
    } else {
        error_log("SQL prepare error: " . $conn->error);
        return null;
    }
}

$result = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_digitada = $_POST['senha'] ?? '';
    $hash = getUserHashFromSession($conn);

    if ($hash === null) {
        $result = "<span style='color:orange;'>Usuário não encontrado ou não autenticado.</span>";
    } else {
        if (password_verify($senha_digitada, $hash)) {
            header('Location: mudardados.php');
            exit;
        } else {
            $result = "<span style='color:red;'>❌ Senha incorreta!</span>";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Verificar Senha</title>
</head>
<body style="font-family: Arial; text-align:center; margin-top:40px;">
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
    <h2>Confirme sua senha</h2>
    <form method="POST">
        <input type="password" name="senha" placeholder="Digite sua senha" required>
        <button type="submit">Verificar</button>
    </form>
    <br>
    <?= $result ?>
</body>
</html>
