<?php
require('conexao.php');
session_start();

$foto_perfil_url = $_SESSION['usuario']['foto'] ?? 'Mídias/perfil.png'; 
$email = $_SESSION['usuario']['email'] ?? '';
$nome = $_SESSION['usuario']['nome'] ?? '';
$telefone = $_SESSION['usuario']['telefone'] ?? '';
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Alunos e Cursos</title>
<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="usuariosadm.css">
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

<main>
<h2>Lista de Alunos e Cursos</h2>
<table>
<tr>
    <th>CPF</th>
    <th>Nome</th>
    <th>Email</th>
    <th>Telefone</th>
    <th>Cursos</th>
    <th>Ações</th>
</tr>

<?php
$sql = "SELECT a.CPF, a.Nome, a.Email, a.Telefone,
               GROUP_CONCAT(c.Nome SEPARATOR ', ') AS cursos
        FROM alunos a
        LEFT JOIN aluno_cursos ac ON a.CPF = ac.CPF_aluno
        LEFT JOIN cursos c ON ac.Id_curso = c.Id_curso
        GROUP BY a.CPF, a.Nome, a.Email, a.Telefone";

$result = $conn->query($sql);

if ($result && $result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>{$row['CPF']}</td>";
        echo "<td>{$row['Nome']}</td>";
        echo "<td>{$row['Email']}</td>";
        echo "<td>{$row['Telefone']}</td>";
        echo "<td>{$row['cursos']}</td>";
        echo "<td>
                <form action='apagar_aluno.php' method='post' style='display:inline; margin:0; padding:0;'>
                    <input type='hidden' name='CPF' value='{$row['CPF']}'>
                    <input type='submit' value='Deletar' class='btn-deletar'>
                </form>
              </td>";
        echo "</tr>";
    }
} else {
    echo "<tr><td colspan='6' style='text-align:center;'>Nenhum aluno cadastrado.</td></tr>";
}
?>
</table>
</main>
</body>
</html>
