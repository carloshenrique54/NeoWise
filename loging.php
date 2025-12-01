<?php
session_start();
require('conexao.php');

$email = $_POST['email'] ?? '';
$senha = $_POST['senha'] ?? '';

$stmt = $conn->prepare('SELECT cpf, nome, senhahash, telefone,acesso FROM alunos WHERE email = ?');
$stmt->bind_param('s', $email);
$stmt->execute();
$u = $stmt->get_result()->fetch_assoc();

if (!$u) {
    header("Location: login.php?erro=2");
    exit;
}

if (!password_verify($senha, $u['senhahash'])) {
    header("Location: login.php?erro=1");
    exit;
}

$_SESSION['usuario'] = [
    'cpf' => $u['cpf'],
    'nome' => $u['nome'],
    'email' => $email,
    'telefone' => $u['telefone'],
    'acesso' => $u['acesso'],
    'curtidas' => $u ['curtidas']
];

header("Location: index.php");
exit;