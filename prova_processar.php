<?php
session_start();
require('conexao.php');
require('foto-perfil.php');

if (!isset($_POST['id_prova'])) {
    header("Location: index.php");
    exit();
}

if (!isset($_SESSION['usuario'])) {
    die("Usuário não logado!");
}

$cpf = $_SESSION['usuario']['cpf'];
$id_prova = intval($_POST['id_prova']);

// ===========================
// BUSCAR PROVA
// ===========================
$sqlProva = "SELECT * FROM provas WHERE id = ?";
$stmtProva = $conn->prepare($sqlProva);
$stmtProva->bind_param("i", $id_prova);
$stmtProva->execute();
$prova = $stmtProva->get_result()->fetch_assoc();

if (!$prova) {
    die("Prova não encontrada!");
}

// ===========================
// BUSCAR QUESTÕES DA PROVA
// ===========================
$sqlQuestoes = "SELECT * FROM questoes WHERE id_prova = ?";
$stmtQ = $conn->prepare($sqlQuestoes);
$stmtQ->bind_param("i", $id_prova);
$stmtQ->execute();
$questoes = $stmtQ->get_result();

$totalQuestoes = $questoes->num_rows;
$acertos = 0;
$respostas_usuario = [];

// ===========================
// PROCESSAR RESPOSTAS
// ===========================
while ($q = $questoes->fetch_assoc()) {

    $qid = $q['id'];
    $campo = "q{$qid}";
    $resposta = $_POST[$campo] ?? null;
    $respostas_usuario[$qid] = $resposta;

    if ($resposta && strtoupper($resposta) === strtoupper($q['correta'])) {
        $acertos++;
    }
}

$nota = $acertos;

if ($nota >= 7){
    $aprovado = true;
} else {
    $aprovado = false;
}

// ===========================
// IMPEDIR REFAZER PROVA
// ===========================
$sqlCheck = "SELECT id_aluno_prova FROM provas_aluno 
             WHERE cpf_aluno = ? AND id_prova = ?";
$stmtCheck = $conn->prepare($sqlCheck);
$stmtCheck->bind_param("si", $cpf, $id_prova);
$stmtCheck->execute();
$jaFez = $stmtCheck->get_result()->fetch_assoc();

if (!$jaFez) {

    // ===========================
    // SALVAR RESULTADO
    // ===========================
    $sqlInsert = "INSERT INTO provas_aluno 
    (id_prova, cpf_aluno, feito, nota)
    VALUES (?, ?, 'sim', ?)";

    $stmtInsert = $conn->prepare($sqlInsert);
    $stmtInsert->bind_param("isi", $id_prova, $cpf, $nota);
    $stmtInsert->execute();

    $id_prova_aluno = $stmtInsert->insert_id;

    // ===========================
    // SALVAR RESPOSTAS
    // ===========================
    $sqlResp = "INSERT INTO respostas (id_prova_aluno, id_questao, resposta)
                VALUES (?, ?, ?)";

    $stmtResp = $conn->prepare($sqlResp);

    foreach ($respostas_usuario as $qid => $resp) {
        $stmtResp->bind_param("iis", $id_prova_aluno, $qid, $resp);
        $stmtResp->execute();
    }
}

// Atualiza ponteiro das questões para exibir gabarito
$stmtQ->execute();
$questoes = $stmtQ->get_result();

?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>NeoWise</title>
    <link rel="stylesheet" href="prova_processar.css">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
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
<div id="prova-status">
<h1><?php echo htmlspecialchars($prova['titulo']); ?></h1>

<p><strong>Questões respondidas:</strong> <?php echo $totalQuestoes; ?></p>
<p><strong>Acertos:</strong> <?php echo $acertos; ?></p>
<p><strong>Nota mínima:</strong> <?php echo $prova['nota_minima']; ?></p>
<p><strong>Status:</strong> 
    <?php echo $aprovado ? "<span style='color: green;'>Aprovado <i class='fa-solid fa-check'></i></span>" 
                         : "<span style='color: red;'>Reprovado <i class='fa-solid fa-x'></i></span>"; ?>
</p>
<?php echo $aprovado ? "<a id='voltar' href='curso.php?id={$prova['id_curso']}'>Página do curso</a>" : "<a id='voltar' href='curso.php?id={$prova['id_curso']}'>Tentar novamente</a>"; ?>
</div>
<main>
<h1>Gabarito</h1>
<hr>
<?php
$questoes->data_seek(0);

while ($q = $questoes->fetch_assoc()) {
    $uid = $q['id'];
    $resp = $respostas_usuario[$uid] ?? "-";
    
    echo "<p><strong>{$q['enunciado']}</strong><br>";
    echo "Sua resposta: <b>$resp</b><br>";
    echo "Correta: <b>{$q['correta']}</b>";
    
    if ($resp === $q['correta']) {
        echo " — <span id='correta'><i class='fa-solid fa-check'></i></span>";
    } else {
        echo " — <span id='incorreta'><i class='fa-solid fa-x'></i></span>";
    }
    
    echo "</p><hr>";
}
?>
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
<script src="tema.js"></script>

</body>
</html>