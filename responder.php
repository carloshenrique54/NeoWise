<?php
session_start();
require('conexao.php');
require ('foto-perfil.php');

if ($_SESSION['usuario']['acesso'] != 1) {
    header('Location: index.php'); 
    exit();
}

$sql = "SELECT * FROM mensagem WHERE respondido = 'NÃO'"; 
$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resposta'], $_POST['id_mensagem'])) {
    $resposta = filter_input(INPUT_POST, 'resposta', FILTER_SANITIZE_STRING);
    $id_mensagem = filter_input(INPUT_POST, 'id_mensagem', FILTER_SANITIZE_NUMBER_INT);

    if (!empty($resposta)) {
        $sql_update = "UPDATE mensagem SET respondido = 'SIM', respostas = ? WHERE id_mensagem = ?";
        if ($stmt_update = $conn->prepare($sql_update)) {
            $stmt_update->bind_param("si", $resposta, $id_mensagem);
            $stmt_update->execute();
        }
        echo "<script>alert('Resposta enviada com sucesso!'); window.location.href='".$_SERVER['PHP_SELF']."';</script>";
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise</title>
    <link rel="stylesheet" href="header.css">
    <link rel="icon" href="Mídias/icone.ico">
    <link rel="stylesheet" href="responder.css">
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
                $primeiro_nome = htmlspecialchars($partes_nome[0]);
                echo '<a href="perfil.php"><li id="usuario_nome">Olá, <h3>' . $primeiro_nome . '</h3></li></a>';
                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>
<div id="responderModal" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Responder Mensagem</h3>
        <form method="POST" id="respostaForm">
            <input type="hidden" name="id_mensagem" id="modal_id">
            <div><strong>Assunto:</strong> <span id="modal_assunto"></span></div>
            <div><strong>Mensagem:</strong> <span id="modal_mensagem"></span></div>
            <div>
                <label for="resposta">Sua Resposta:</label>
                <textarea name="resposta" id="modal_resposta" rows="5" required></textarea>
            </div>
            <button type="submit">Enviar</button>
        </form>
    </div>
</div>    
<main>
    <h2>Mensagens não respondidas dos usuários</h2>
    <?php
    if ($result->num_rows > 0) {
        echo "<table border='1'>
                <tr>
                    <th>ID</th>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Assunto</th>
                    <th>Mensagem</th>
                    <th>Ação</th>
                </tr>";
        while ($row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>{$row['id_mensagem']}</td>
                    <td>{$row['nome_mensagem']}</td>
                    <td>{$row['email_mensagem']}</td>
                    <td>{$row['assunto_mensagem']}</td>
                    <td>{$row['mensagem_texto']}</td>
                    <td><button class='responder-btn' 
                                data-id='{$row['id_mensagem']}' 
                                data-nome='{$row['nome_mensagem']}'
                                data-email='{$row['email_mensagem']}'
                                data-assunto='{$row['assunto_mensagem']}'
                                data-mensagem='{$row['mensagem_texto']}'>Responder</button></td>
                  </tr>";
        }
        echo "</table>";
    } else {
        echo "<h4>Não há mensagens não respondidas.</h4>";
    }
    ?>
</main>
<script src="tema.js"></script>
<script src="responder.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@emailjs/browser@4/dist/email.min.js"></script>
<div id="modal-sucesso">
    <div class="modal-conteudo">
        <h2>Resposta enviada!</h2>
        <p>A resposta foi enviada para o aluno com sucesso.</p>
    </div>
</div>

<script>
(function () {
    emailjs.init("yexqvxDUHrrXPU6Wf");
})();
</script>

<script src="responder.js"></script>
</body>
</html>