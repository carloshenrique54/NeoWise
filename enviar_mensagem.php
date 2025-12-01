<?php
require('conexao.php'); 

$nome       = $_POST['nome'];
$email      = $_POST['email'];
$mensagem      = $_POST['mensagem'];
$assunto   = $_POST['assunto'];

$stmt = $conn->prepare("INSERT INTO mensagem (nome_mensagem, email_mensagem, mensagem_texto, assunto_mensagem) VALUES (?, ?, ?, ?)");

$stmt->bind_param("ssss", $nome, $email, $mensagem, $assunto);

if ($stmt->execute()) {
    header("Location: contato.php?status=sucesso");
    exit; 

} else {
    echo "Erro ao enviar mensagem: " . $stmt->error;
}

$stmt->close();
$conn->close();