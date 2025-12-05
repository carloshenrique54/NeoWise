<?php
require('conexao.php'); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$foto_perfil_url = $_SESSION['usuario']['foto'] ?? 'Mídias/perfil.png'; 
$email = $_SESSION['usuario']['email'] ?? '';
$nome = $_SESSION['usuario']['nome'] ?? '';
$telefone = $_SESSION['usuario']['telefone'] ?? '';
$aluno_cpf = $_SESSION['usuario']['cpf'] ?? null;

if ($aluno_cpf && isset($conn) && $conn) {
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

$cursos_em_andamento = [];
$certificados_concluidos = [];

if ($aluno_cpf && isset($conn) && $conn) {
    $sql_cursos = "
        SELECT 
            ac.progresso, 
            c.Nome AS nome_curso,
            c.id_curso,
            c.imagem AS foto_curso
        FROM 
            aluno_cursos ac
        JOIN 
            cursos c ON ac.id_curso = c.id_curso
        WHERE 
            ac.cpf_aluno = ?
        ORDER BY 
            ac.progresso DESC
    ";
    if ($stmt_cursos = $conn->prepare($sql_cursos)) {
        $stmt_cursos->bind_param("s", $aluno_cpf);
        $stmt_cursos->execute();
        $resultado = $stmt_cursos->get_result();
        while ($curso = $resultado->fetch_assoc()) {
            $progresso = intval($curso['progresso']);
            if ($progresso == 100) {
                $certificados_concluidos[] = $curso;
            } else {
                $cursos_em_andamento[] = $curso;
            }
        }
        $stmt_cursos->close();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise - Perfil</title>
    <link rel="icon" href="Mídias/icone.ico">
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="perfil.css">
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
                $primeiro_nome = htmlspecialchars($partes_nome[0]);
                echo '<a href="perfil.php"><li id="usuario_nome">Olá, <h3>' . $primeiro_nome . '</h3></li></a>';
                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>

<main class="perfil-container">

    <aside class="perfil-lateral">
        <div class="perfil-header">
            <input type="file" id="upload-foto-perfil" name="nova_foto" accept="image/*" style="display:none;">
            <div class="perfil-foto-wrapper clicavel" id="botao-foto-perfil">
                <img src="<?php echo $foto_perfil_url; ?>" alt="Foto de Perfil" class="perfil-foto">
                <div class="overlay-editar">Trocar Foto</div>
            </div>
            <h3 class="perfil-nome"><?php echo htmlspecialchars($nome); ?></h3>
            <p class="perfil-email"><?php echo htmlspecialchars($email); ?></p>
        </div>

        <div class="perfil-info">
            <h4>Informações de login:</h4>
            <p>E-mail: <span><?php echo htmlspecialchars($email); ?></span></p>
            <p>Telefone: <span><?php echo htmlspecialchars($telefone); ?></span></p>
            <form action="alterardados.php" method="POST">
                <button type="submit" class="btn-alterar-dados">Alterar Dados</button>
            </form>
            <a href="logout.php" id="btn-logout">Sair</a>
        </div>
    </aside>

    <section class="perfil-principal">
        <h2>Meus Cursos</h2>
        <div class="bloco-cursos">
            <?php if (empty($cursos_em_andamento)): ?>
                <p class="mensagem-sem-cursos">Você não tem cursos em andamento. Visite a página de <a href="cursos.php">Cursos</a>!</p>
            <?php else: ?>
                <?php foreach ($cursos_em_andamento as $curso): 
                    $titulo = htmlspecialchars($curso['nome_curso']);
                    $progresso = intval($curso['progresso']);
                    $link = "curso.php?id=" . urlencode($curso['id_curso']);
                    $foto = htmlspecialchars($curso['foto_curso'] ?? 'curso-backend.png');
                ?>
              <div class="curso-card-<?php echo $progresso == 100 ? 'completo' : 'incompleto'; ?>">
                <img src="Mídias/Cursos/<?php echo $foto; ?>" alt="">
                <div class="card-detalhes"> 
                    <span class="curso-label"><?php echo $progresso == 100 ? 'CURSO CONCLUÍDO' : 'CURSO'; ?></span> 
                    <span class="curso-titulo"><?php echo $titulo; ?></span> 
                    <p class="curso-status"><?php echo $progresso == 100 ? 'Completo' : 'Em andamento ' . $progresso . "%"; ?></p> 
                    <a href="<?php echo $link; ?>" class="btn-curso">Página do Curso</a> 
                    <?php if ($progresso == 100): ?> 
                    <div class="progresso-circulo">
                    <button class="btn-download" title="Baixar Certificado">↓</button> <?php endif; ?> </div>
    </div>
</div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>

        <h2>Meus Certificados</h2>
        <div class="bloco-certificados">
            <?php if (empty($certificados_concluidos)): ?>
                <p class="mensagem-sem-cursos">Conclua um curso (100%) para obter seu certificado!</p>
            <?php else: ?>
                <?php foreach ($certificados_concluidos as $curso): 
                    $titulo = htmlspecialchars($curso['nome_curso']);
                    $link = "curso.php?id=" . urlencode($curso['id_curso']);
                    $foto = htmlspecialchars($curso['foto_curso'] ?? 'Mídias/backend completa.png');
                ?>
                <div class="curso-card-completo">
                    <img src="Mídias/Cursos/<?php echo $foto; ?>" alt="<?php echo $titulo; ?>" class="curso-imagem">
                    <div class="card-detalhes">
                        <span class="curso-label">CURSO CONCLUÍDO</span>
                        <span class="curso-titulo"><?php echo $titulo; ?></span>
                        <p class="curso-status">Completo</p>
                        <a href="<?php echo $link; ?>" class="btn-curso">Página do Curso</a>
                    </div>
                </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </section>
</main>

<footer>
    <div id="footer-content">
        <div class="footer-section">
            <h3>FinWise</h3>
            <p>Sua plataforma de cursos online para se destacar no mercado.</p>
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
<script src="perfil.js"></script>

</body>
</html>
