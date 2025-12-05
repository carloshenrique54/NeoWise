<?php
require 'conexao.php';
include('foto-perfil.php');
if (session_status() === PHP_SESSION_NONE) session_start();

// Pega lista de cursos
$sql = "SELECT * FROM cursos";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Gerenciar Cursos</title>
<link rel="stylesheet" href="header.css">
<link rel="stylesheet" href="adicionar.css">
<style>
/* Modal */
.modal { display: none; position: fixed; z-index: 999; left:0; top:0; width:100%; height:100%; background: rgba(0,0,0,0.5);}
.modal-content { background:#fff; margin: 10% auto; padding:20px; width:90%; max-width:500px; border-radius:10px; position:relative; }
.close { position:absolute; right:15px; top:10px; font-size:25px; cursor:pointer;}
form { display:flex; flex-direction:column; gap:10px;}
input, textarea { padding:8px; border:1px solid #ccc; border-radius:4px; width:100%; }
#modalAdd button { padding:10px; background:#E6C72B; color:#fff; border:none; border-radius:4px; cursor:pointer; }
#modalEdit button { padding:10px; background:#E6C72B; color:#fff; border:none; border-radius:4px; cursor:pointer; }
#modalAdd button:hover { background:#E6C72B; }
#modalEdit button:hover { background:#E6C72B; }
</style>
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
<div id="tabela">
<div id="topo-tabela">
    <h2>Lista de Cursos</h2>
    <button id="btnAddCurso">Adicionar Curso</button>
</div>
<table>
<tr>
    <th>ID</th><th>Nome</th><th>Categoria</th><th>Descrição</th>
    <th>Conteúdo</th><th>Atividades</th><th>Benefícios</th>
    <th>Preço</th><th>Preço Parcelado</th><th>Ações</th>
</tr>
<?php
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        echo "<tr>
            <td>{$row['Id_curso']}</td>
            <td>{$row['Nome']}</td>
            <td>{$row['Categoria']}</td>
            <td>{$row['Descrição']}</td>
            <td>{$row['Conteudo']}</td>
            <td>{$row['Atividades']}</td>
            <td>{$row['Beneficios']}</td>
            <td>{$row['Preço']}</td>
            <td>{$row['Preçoparce']}</td>
            <td class='butoes'>
                <button class='btn-editar' 
                    data-id='{$row['Id_curso']}'
                    data-nome='{$row['Nome']}'
                    data-categoria='{$row['Categoria']}'
                    data-descricao='{$row['Descrição']}'
                    data-conteudo='{$row['Conteudo']}'
                    data-atividades='{$row['Atividades']}'
                    data-beneficios='{$row['Beneficios']}'
                    data-preco='{$row['Preço']}'
                    data-precoparce='{$row['Preçoparce']}'>Editar</button>
                <form action='apagar.php' method='post' style='display:inline-block;'>  
                    <input type='hidden' name='id' value='{$row['Id_curso']}'>
                    <button class='btn-deletar' id='{$row['Id_curso']}'>Deletar</button>
                </form>
            </td>
        </tr>";
    }
} else {
    echo "<tr><td colspan='10' style='text-align:center;'>Nenhum curso cadastrado.</td></tr>";
}
?>
</table>
</div>
</main>

<div id="modalAdd" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Adicionar Curso</h3>
        <form action="add.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="acao" value="adicionar">
            <input type="text" name="nome_curso" placeholder="Nome do curso" required>
            <input type="text" name="categoria" placeholder="Categoria" required>
            <textarea name="descricao" placeholder="Descrição" required></textarea>
            <input type="text" name="conteudo" placeholder="Conteúdo" required>
            <input type="text" name="atividades" placeholder="Atividades" required>
            <input type="text" name="beneficios" placeholder="Benefícios" required>
            <input type="number" name="preco" placeholder="Preço" required>
            <input type="number" name="preco_parce" placeholder="Preço Parcelado" required>
            <input type="file" name="imagem" placeholder="Coloque a imagem" required>
            <button type="submit">Adicionar Curso</button>
        </form>
    </div>
</div>

<div id="modalEdit" class="modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h3>Editar Curso</h3>
        <form method="POST" action="editar_curso.php">
            <input type="hidden" name="id" id="edit_id">
            <input type="text" name="e_nome" id="edit_nome" placeholder="Nome do curso" required>
            <input type="text" name="e_categoria" id="edit_categoria" placeholder="Categoria" required>
            <input type="text" name="e_descricao" id="edit_descricao" placeholder="Descrição" required>
            <input type="text" name="e_conteudo" id="edit_conteudo" placeholder="Conteúdo" required>
            <input type="text" name="e_atividades" id="edit_atividades" placeholder="Atividades" required>
            <input type="text" name="e_beneficios" id="edit_beneficios" placeholder="Benefícios" required>
            <input type="text" id="preco" name="preco" placeholder="Preço" required>
            <input type="text" id="preco_parce" name="preco_parce" placeholder="Preço Parcelado" required>
            <button type="submit">Salvar Alterações</button>
        </form>
    </div>
</div>

<script>
const modalAdd = document.getElementById('modalAdd');
const modalEdit = document.getElementById('modalEdit');

document.getElementById('btnAddCurso').onclick = () => modalAdd.style.display = 'block';

// Fechar modais
document.querySelectorAll('.close').forEach(span => {
    span.onclick = () => { span.parentElement.parentElement.style.display = 'none'; }
});
window.onclick = e => {
    if (e.target == modalAdd) modalAdd.style.display = 'none';
    if (e.target == modalEdit) modalEdit.style.display = 'none';
};

// Editar curso
document.querySelectorAll('.btn-editar').forEach(btn => {
    btn.onclick = () => {
        modalEdit.style.display = 'block';
        document.getElementById('edit_id').value = btn.dataset.id;
        document.getElementById('edit_nome').value = btn.dataset.nome;
        document.getElementById('edit_categoria').value = btn.dataset.categoria;
        document.getElementById('edit_descricao').value = btn.dataset.descricao;
        document.getElementById('edit_conteudo').value = btn.dataset.conteudo;
        document.getElementById('edit_atividades').value = btn.dataset.atividades;
        document.getElementById('edit_beneficios').value = btn.dataset.beneficios;
        document.getElementById('edit_preco').value = btn.dataset.preco;
        document.getElementById('edit_preco_parce').value = btn.dataset.precoparce;
    }
});

window.onload = () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'sucesso') {
        alert('Curso adicionado com sucesso!');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
};window.onload = () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'sucesso') {
        alert('Curso adicionado com sucesso!');
        window.history.replaceState({}, document.title, window.location.pathname);
    }
};

function formatarMoeda(input) {
    let valor = input.value;

    valor = valor.replace(/\D/g, "");

    if (valor.length === 0) {
        input.value = "";
        return;
    }

    // Converte para centavos
    valor = (parseInt(valor) / 100).toFixed(2);

    // Troca ponto por vírgula
    valor = valor.replace(".", ",");

    // Coloca os pontos de milhar
    valor = valor.replace(/\B(?=(\d{3})+(?!\d))/g, ".");

    input.value = "R$ " + valor;
}

document.getElementById("preco").addEventListener("input", function () {
    formatarMoeda(this);
});

document.getElementById("preco_parce").addEventListener("input", function () {
    formatarMoeda(this);
});
</script>

</body>
</html>