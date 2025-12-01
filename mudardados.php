<?php
session_start();
require 'conexao.php';
require 'foto-perfil.php';

if (empty($_SESSION['usuario']['cpf'])) {
    header('Location: login.php');
    exit;
}

$cpf = $_SESSION['usuario']['cpf'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $telefone = trim($_POST['telefone'] ?? '');
    $senha_nova = $_POST['senha_nova'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $msg = "<span style='color:red'>Email inválido.</span>";
    } else {
        $sqlCheck = "SELECT cpf FROM alunos WHERE email = ? AND cpf <> ? LIMIT 1";
        $stmt2 = $conn->prepare($sqlCheck);
        $stmt2->bind_param('ss', $email, $cpf);
        $stmt2->execute();
        $r2 = $stmt2->get_result()->fetch_assoc();
        $stmt2->close();

        if ($r2) {
            $msg = "<span style='color:red'>Esse email já está em uso.</span>";
        } else {
            if (!empty($senha_nova)) {
                $newHash = password_hash($senha_nova, PASSWORD_DEFAULT);
                $sqlUp = "UPDATE alunos SET nome = ?, email = ?, telefone = ?, senhahash = ? WHERE cpf = ? LIMIT 1";
                $stmt3 = $conn->prepare($sqlUp);
                $stmt3->bind_param('sssss', $nome, $email, $telefone, $newHash, $cpf);
            } else {
                $sqlUp = "UPDATE alunos SET nome = ?, email = ?, telefone = ? WHERE cpf = ? LIMIT 1";
                $stmt3 = $conn->prepare($sqlUp);
                $stmt3->bind_param('ssss', $nome, $email, $telefone, $cpf);
            }

            if ($stmt3->execute()) {
                $_SESSION['usuario']['nome'] = $nome;
                $_SESSION['usuario']['email'] = $email;
                $msg = "<span style='color:green'>Dados atualizados com sucesso.</span>";
                header('location:index.php');
            } else {
                $msg = "<span style='color:red'>Erro ao atualizar. Tente novamente.</span>";
            }
            $stmt3->close();
        }
    }
}

$sql = "SELECT nome, email, telefone FROM alunos WHERE cpf = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $cpf);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$stmt->close();

$nome = htmlspecialchars($user['nome'] ?? '');
$email = htmlspecialchars($user['email'] ?? '');
$telefone = htmlspecialchars($user['telefone'] ?? '');
$msg = $msg ?? '';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="utf-8">
    <title>NeoWise</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="mudardados.css">
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
    <form method="POST">
        <h1>Alterar dados</h1>
        <label>Nome</label><br>
        <input type="text" name="nome" value="<?= $nome ?>" required><br><br>

        <label>E-mail</label><br>
        <input type="email" name="email" value="<?= $email ?>" required><br><br>

        <label>Telefone</label><br>
        <input type="text" name="telefone" value="<?= $telefone ?>"><br><br>

        <label>Senha nova (opcional)</label><br>
        <input type="password" name="senha_nova"><br><br>

        <button type="submit">Salvar alterações</button>
    </form>
    <br>
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
</body>
</html>
