<?php
require('conexao.php');

// Pegando os campos normais
$nome = $_POST['nome_curso'];
$categoria = $_POST['categoria'];
$descricao = $_POST['descricao'];
$conteudo = $_POST['conteudo'];
$atividades = $_POST['atividades'];
$beneficios = $_POST['beneficios'];
$preco = $_POST['preco']; 
$preco = preg_replace('/[^0-9,]/', '', $preco); 
$preco = str_replace(',', '.', $preco); 

// ==============================
// 💾 UPLOAD DA IMAGEM
// ==============================
if (!isset($_FILES['imagem']) || $_FILES['imagem']['error'] !== 0) {
    die("Erro ao receber imagem.");
}

// pasta onde vai salvar
$pasta = "Mídias/Cursos/";

if (!is_dir($pasta)) {
    mkdir($pasta, 0777, true);
}

// gera nome único
$nomeImagem = uniqid() . "_" . basename($_FILES['imagem']['name']);
$caminhoFinal = $pasta . $nomeImagem;

// move arquivo
if (!move_uploaded_file($_FILES['imagem']['tmp_name'], $caminhoFinal)) {
    die("Erro ao salvar a imagem.");
}

// caminho salvo no banco
$imagem = $caminhoFinal;

// ==============================
// INSERIR NO BANCO
// ==============================
$stmt = $conn->prepare("
    INSERT INTO cursos 
    (`Nome`, `Categoria`, `Descrição`, `Conteudo`, `Atividades`, `Beneficios`, `Preço`, `Preçoparce`, `Imagem`)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "sssssssss",
    $nome,
    $categoria,
    $descricao,
    $conteudo,
    $atividades,
    $beneficios,
    $preco,
    $preco_parce,
    $imagem
);

if ($stmt->execute()) {
    header("Location: adicionar.php?status=sucesso");
} else {
    echo "Erro ao adicionar curso: " . $stmt->error;
}

$stmt->close();