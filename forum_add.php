<?php

require('conexao.php'); 

session_start();

$cpf = $_SESSION['usuario']['cpf'] ?? null; 
$msg = $_POST['mensagem'] ?? '';
$top = $_POST['topico'] ?? '';

if ($cpf === null) {
    echo "Erro: Você precisa estar logado para enviar uma mensagem.";
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO forum (cpfmensagem, mensagem, topico) VALUES (?, ?, ?)
"); 

if ($stmt === false) {
    die("Erro ao preparar a query: " . $conn->error);
}

$stmt->bind_param(
    "sss",
    $cpf, 
    $msg,
    $top
);

if ($stmt->execute()) {
    header('Location: forum.php');
    exit;
} else {
    echo "Erro ao inserir mensagem: " . $stmt->error;
}

$stmt->close();

?>