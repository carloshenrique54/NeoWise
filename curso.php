<?php

// 1. INÍCIO DA SESSÃO (Essencial para usar $_SESSION)
session_start();

require('conexao.php'); 
require('foto-perfil.php'); // Assumindo que este arquivo define $foto_perfil_url

$comprou = false;
$dadosCurso = null;
$cursoId = $_GET['id'] ?? null;
$usuarioCpf = $_SESSION['usuario']['cpf'] ?? null; 
$acesso = $_SESSION['usuario']['acesso'] ?? 0;
$aprovado = false;
$id_prova = null;


// 2. BUSCA DETALHES DO CURSO

if ($cursoId) {
    // Busca o ID da prova associada ao curso, se houver
    $sql_prova_id = "SELECT id FROM provas WHERE id_curso = ?";
    if ($stmt_prova = $conn->prepare($sql_prova_id)) {
        $stmt_prova->bind_param("i", $cursoId);
        $stmt_prova->execute();
        $res_prova = $stmt_prova->get_result();
        if ($row_prova = $res_prova->fetch_assoc()) {
            $id_prova = $row_prova['id'];
        }
        $stmt_prova->close();
    }

    // Busca detalhes do curso
    $sql_curso = "SELECT `Id_curso`, `Nome`, `Descrição`, `Conteudo`, `Atividades`, `Beneficios`, `Preço`, `Preçoparce`, `Imagem` FROM cursos WHERE `Id_curso` = ?";

    if ($stmt = $conn->prepare($sql_curso)) {
        $stmt->bind_param("i", $cursoId);
        $stmt->execute();
        $resultado = $stmt->get_result();
        
        if ($resultado->num_rows === 1) {
            $curso = $resultado->fetch_assoc();
        }
        $stmt->close();
    }
}

// 3. REDIRECIONA SE O CURSO NÃO FOR ENCONTRADO
if (!$curso) {
    // Fecha a conexão antes de redirecionar
    $conn->close();
    header('Location: cursos.php?status=notfound');
    exit; 
}


// 4. LÓGICA DE COMPRA E APROVAÇÃO (Só roda se o usuário estiver logado)
if ($usuarioCpf) {
    $sql = "SELECT * FROM aluno_cursos WHERE cpf_aluno = ? AND id_curso = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("si", $usuarioCpf, $cursoId);
    $stmt->execute();
    $res = $stmt->get_result();

    if ($res->num_rows > 0) {
        $comprou = true;
        $dadosCurso = $res->fetch_assoc(); 
    }
    $stmt->close();
    
    // 4.2. Verifica o status de aprovação da prova (se a prova existir)
    if ($id_prova) {
        $sql_status = "SELECT nota FROM provas_aluno WHERE cpf_aluno = ? AND id_prova = ?";
        if ($stmt_status = $conn->prepare($sql_status)) {
            $stmt_status->bind_param("si", $usuarioCpf, $id_prova);
            $stmt_status->execute();
            $result_status = $stmt_status->get_result();
            if ($dados = $result_status->fetch_assoc()) {
                $nota = $dados['nota'] ?? 0;
                if ($nota >= 7.0) { 
                    $aprovado = true;
                }
            }
            $stmt_status->close();
        }
    }
}

// 5. FUNÇÃO DE FORMATAÇÃO
function formatar_lista($texto_virgula) {
    if (empty($texto_virgula)) {
        return "<ul><li>Nenhuma informação detalhada disponível.</li></ul>";
    }
    $itens = array_filter(array_map('trim', explode(',', $texto_virgula)));
    $html = '<ul>';
    foreach ($itens as $item) {
        $html .= "<li>" . htmlspecialchars($item) . "</li>";
    }
    $html .= '</ul>';
    return $html;
}

// 6. PREPARAÇÃO DE VARIÁVEIS PARA O HTML
$id = htmlspecialchars($curso['Id_curso']);
$titulo = htmlspecialchars($curso['Nome']);
$descricao = htmlspecialchars($curso['Descrição']);

// *** CORREÇÃO DA IMAGEM: Lógica para tratar caminhos inconsistentes no DB ***
$imagem_db = $curso['Imagem'];
$caminho_base = 'Mídias/Cursos/';
if ($imagem_db && strpos($imagem_db, $caminho_base) === 0) {
    // Caso o valor do DB já contenha o caminho completo (formato incorreto de salvamento)
    $caminho_final = htmlspecialchars($imagem_db);
} else {
    // Caso o valor do DB contenha apenas o nome do arquivo (formato correto) ou seja NULL
    $fallback_img = $imagem_db ?? 'default.png'; 
    $caminho_final = $caminho_base . htmlspecialchars($fallback_img);
}
// *** FIM DA CORREÇÃO DA IMAGEM ***

$precoVista = htmlspecialchars($curso['Preço']);
$precoParcelado = htmlspecialchars($curso['Preçoparce']);

$conteudo_html = formatar_lista($curso['Conteudo'] ?? '');
$atividades_html = formatar_lista($curso['Atividades'] ?? '');
$beneficios_html = formatar_lista($curso['Beneficios'] ?? '');

$preco_limpo = filter_var($curso['Preçoparce'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
$link_pagamento = "pagamento.php?curso_id={$id}&preco={$preco_limpo}";
// 7. FECHA CONEXÃO APÓS TODAS AS BUSCAS
$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise - <?php echo $titulo; ?></title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="curso.css">
    <link rel="icon" href="Mídias/icone.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
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
        <div id="parte1">
            <h2 id="curso-titulo"><?php echo $titulo; ?></h2>
            
            <p id="curso-descricao"><?php echo $descricao; ?></p>

            <h3>Conteúdo do Curso</h3>
            <div id="curso-conteudo" class="com-imagem">
                <?php echo $conteudo_html; ?>
            </div>

            <h3>Atividades do Curso</h3>
            <div id="curso-atividades">
                <?php echo $atividades_html; ?>
            </div>
        </div>
        <div id="parte2">
            <div id="curso-card">
                <img id="curso-imagem" src="<?php echo $caminho_final; ?>" alt="Imagem do curso: <?php echo $titulo; ?>" />
                
                <div id="curso-beneficios" class="com-imagem">
                    <?php echo $beneficios_html; ?>
                </div>
            </div>

            <div id="preco">
                <?php if (!isset($_SESSION['usuario'])): ?>

    <a id="pagamento" href="login.php">Faça login para assinar</a>

    <?php elseif (!$comprou): ?>
        <h3 id="curso-preco">R$ <?php echo $precoVista; ?> à vista no PIX</h3>
                
                <p id="curso-preco-parcelado">R$ <?php echo $precoParcelado; ?> até 6x
                
                
                    sem juros no cartão</p>

    <a id="pagamento" href="<?php echo $link_pagamento; ?>">Assine agora</a>

    <?php else: ?>

    <div class="area-aluno">
        <h3>Conteudo do curso</h3>
        <?php if ($comprou && $aprovado): ?>
        <a href="Mídias/Certificado.pdf" download class="btn-curso" id="btnCertificado">
            <i class="fa-solid fa-file-arrow-down"></i> Baixar Certificado
        </a>
<?php endif; ?>
        <?php
        if (!$aprovado){
            echo '<a class="btn-curso" href="prova.php?curso=' . $id . '"><i class="fa-solid fa-file-circle-check"></i> Fazer Prova</a>';
        } else {
            echo '<p class="aprovado-msg"><i class="fa-solid fa-graduation-cap"></i> Parabéns! Curso Aprovado.</p>';
        }
        ?>
        <p>Progresso: <?php echo $dadosCurso['progresso'] ?? '0'; ?>%</p>
    </div>

<?php endif; ?>
            </div>
        </div>
    </main>
    <footer>
    <div id="footer-content">
        <div class="footer-section">
            <h3>NeoWise</h3>
            <p>Sua plataforma de cursos online para se destacar no mercado.</p>
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