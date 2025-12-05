<?php
require('conexao.php'); 
require('foto-perfil.php');


$cursoId = $_GET['curso'];

$sql = "SELECT * FROM provas WHERE id_curso = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cursoId);
$stmt->execute();
$prova = $stmt->get_result()->fetch_assoc();

$provaId = $prova['id'];
$questao = 0;

$sql = "SELECT * FROM questoes WHERE id_curso = ? ORDER BY id ASC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $cursoId);
$stmt->execute();
$questoes = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise - Prova</title>
    <link rel="stylesheet" href="prova.css">
    <link rel="stylesheet" href="header.css">
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
<button id="voltar" onclick="window.location.href='curso.php?id=<?php echo $cursoId; ?>'">Voltar</button>
<form action="prova_processar.php" method="POST" id="prova">
    <h2><?php echo htmlspecialchars($prova['titulo']); ?></h2>
    <input type="hidden" name="id_prova" value="<?php echo $prova['id']; ?>">
<?php
while ($q = $questoes->fetch_assoc()) {
    $questao++;
    echo "<div class='questao'>";
    echo "<p> {$questao}. {$q['enunciado']}</p>";
    echo "<div class='alternativas'>";
    echo "<label><input type='radio' name='q{$q['id']}' value='A'> {$q['alternativa_a']}</label>";
    echo "<label><input type='radio' name='q{$q['id']}' value='B'> {$q['alternativa_b']}</label>";
    echo "<label><input type='radio' name='q{$q['id']}' value='C'> {$q['alternativa_c']}</label>";
    echo "<label><input type='radio' name='q{$q['id']}' value='D'> {$q['alternativa_d']}</label>";
    echo "</div>";

    echo "</div>";
}
?>
<button type="submit">Enviar Prova</button>
</form>
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
<script src="tema.js"></script>
</body>
</html>