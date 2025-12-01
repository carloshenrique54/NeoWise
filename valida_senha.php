<?php
session_start();
require 'conexao.php';

$cpf = $_SESSION['usuario']['cpf'] ?? null;
$senha = $_POST['senha'] ?? '';

if (!$cpf) {
    echo json_encode(["ok" => false]);
    exit;
}

$sql = "SELECT senhahash FROM alunos WHERE cpf = ? LIMIT 1";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $cpf);
$stmt->execute();
$stmt->bind_result($hash);
$stmt->fetch();
$stmt->close();

if (password_verify($senha, $hash)) {
    echo json_encode(["ok" => true]);
} else {
    echo json_encode(["ok" => false]);
}
