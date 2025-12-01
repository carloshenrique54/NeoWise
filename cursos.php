<?php
session_start();
require('conexao.php'); 
include('foto-perfil.php'); // define $foto_perfil_url

// Sanitiza parâmetros
$termoPesquisa = htmlspecialchars($_GET['search'] ?? '');
$categoriaSelecionada = htmlspecialchars($_GET['cursos'] ?? '');

// Busca todos os cursos sem limite
$sql = "SELECT Id_curso, Categoria, Nome, `Descrição` AS Descricao, Imagem FROM cursos WHERE 1=1";

$params = [];
$tipos = '';

// Categoria
if (!empty($categoriaSelecionada)) {
    $sql .= " AND Categoria = ?";
    $tipos .= 's';
    $params[] = $categoriaSelecionada;
}

// Pesquisa
if (!empty($termoPesquisa)) {
    $sql .= " AND (Nome LIKE ? OR `Descrição` LIKE ?)";
    $tipos .= 'ss';
    $like = '%' . $termoPesquisa . '%';
    $params[] = $like;
    $params[] = $like;
}

$sql .= " ORDER BY Nome ASC";

$cursos = [];
$erro_db = null;

if ($stmt = $conn->prepare($sql)) {
    if (!empty($tipos)) {
        $bind_names = [];
        $bind_names[] = &$tipos;
        for ($i = 0; $i < count($params); $i++) {
            $bind_names[] = &$params[$i];
        }
        call_user_func_array([$stmt, 'bind_param'], $bind_names);
    }

    if ($stmt->execute()) {
        $resultado = $stmt->get_result();
        while ($linha = $resultado->fetch_assoc()) {
            $cursos[] = $linha;
        }
    } else {
        $erro_db = "Erro ao executar consulta: " . $stmt->error;
    }
    $stmt->close();
} else {
    $erro_db = "Erro ao preparar a consulta: " . $conn->error;
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>NeoWise - Cursos</title>
<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="cursos.css">
<link rel="icon" href="Mídias/icone.ico">
</head>
<body>
<header>
    <div id="logo">
        <img src="Mídias/Logo branca.png" alt=" Logo da FinWise" />
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
                $primeiro_nome = explode(' ', $nome_completo)[0];
                $linkPerfil = $_SESSION['usuario']['acesso'] == 0 ? 'perfil.php' : 'perfiladm.php';
                echo '<a href="'.$linkPerfil.'"><li id="usuario_nome">Olá, <h3>'.htmlspecialchars($primeiro_nome).'</h3></li></a>';
                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>

<main>
<form id="pesquisa" method="GET" action="cursos.php"> 
    <h2>Nossos Cursos</h2>
    <input id="search" name="search" type="text" placeholder="Pesquisar curso..." value="<?php echo $termoPesquisa; ?>" />
    <select name="cursos" id="curso-select">
        <option value="">Todos os cursos</option>
        <?php 
        $opcoes = ['Programação'=>'Programação','Marketing'=>'Marketing','Investimentos'=>'Investimentos','Design'=>'Design'];
        foreach($opcoes as $value=>$label):
            $selected = ($categoriaSelecionada==$value)?'selected':'';
        ?>
            <option value="<?php echo $value;?>" <?php echo $selected;?>><?php echo $label;?></option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Buscar</button>
</form>

<div class="carrossel-container">
    <div id="cursos-lista"></div>
    <div class="carrossel-footer">
        <button type="button" id="prev">&lt;</button>
        <span id="pagina-atual"></span>
        <button type="button" id="next">&gt;</button>
    </div>
</div>
</main>
<div id="modal-sucesso">
    <div class="modal-conteudo">
        <h2>✔ Sucesso!</h2>
        <p>Compra realizada com sucesso.</p>
    </div>
</div>
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
                <li><a href="forum.php">Fórum</a></li>
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
<!-- Primeiro define a variável cursos -->
<script>
const cursos = <?php echo json_encode($cursos); ?>;
</script>
<!-- Depois carrega o JS -->
<script>
const container = document.getElementById("cursos-lista");
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');
const paginaSpan = document.getElementById('pagina-atual');
const input = document.getElementById('search');
const select = document.getElementById('curso-select');

const linhasPorPagina = 2;
const colunasPorPagina = 3;
const cursosPorPagina = linhasPorPagina * colunasPorPagina;
let paginaAtual = 1;
let cursosFiltrados = [...cursos]; 

function criarCards(cursosArray){
    container.innerHTML = '';
    cursosArray.forEach(curso=>{
        const img = curso.Imagem ?? 'default.png';
        const card = document.createElement("div");
        card.classList.add("curso");
        card.setAttribute("data-categoria", curso.Categoria);
        card.innerHTML = `
            <img src="Mídias/Cursos/${img}" alt="${curso.Nome}" />
            <h3>${curso.Nome}</h3>
            <p>${curso.Descricao ?? 'Descrição não disponível.'}</p>
            <a href="curso.php?id=${curso.Id_curso}" class="acessar-btn">Acessar Curso</a>
        `;
        container.appendChild(card);
    });
}

const normalizar = str => str ? str.normalize("NFD").replace(/\p{Diacritic}/gu, "").toLowerCase() : '';

function filtrarCursos(){
    const termo = normalizar(input.value);
    const categoria = select.value;
    cursosFiltrados = cursos.filter(curso=>{
        const titulo = normalizar(curso.Nome);
        const catCurso = curso.Categoria;
        return (titulo.includes(termo) || termo==='') && (categoria==='' || categoria===catCurso);
    });
    paginaAtual = 1;
    mostrarPagina();
}

function mostrarPagina(){
    const totalPaginas = Math.ceil(cursosFiltrados.length / cursosPorPagina) || 1;
    if(paginaAtual>totalPaginas) paginaAtual = totalPaginas;
    if(paginaAtual<1) paginaAtual = 1;
    const start = (paginaAtual-1)*cursosPorPagina;
    const end = start+cursosPorPagina;
    criarCards(cursosFiltrados.slice(start,end));
    paginaSpan.textContent = `${paginaAtual} / ${totalPaginas}`;
    prevBtn.disabled = paginaAtual<=1;
    nextBtn.disabled = paginaAtual>=totalPaginas;
}

input.addEventListener('input', filtrarCursos);
select.addEventListener('change', filtrarCursos);
prevBtn.addEventListener('click', ()=>{
    paginaAtual--;
    mostrarPagina();
});
nextBtn.addEventListener('click', ()=>{
    paginaAtual++;
    mostrarPagina();
});

window.onload = () => {
    if (window.location.search.includes('status=comprado')) {
        const modal = document.getElementById("modal-sucesso");
        modal.classList.add("mostrar");
        setTimeout(() => {
            modal.classList.remove("mostrar");
        }, 3000);
    }
};

filtrarCursos();
</script>
</body>
</html>
