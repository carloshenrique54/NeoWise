<?php
require('conexao.php'); 

$nome       = $_POST['nome'];
$email      = $_POST['email'];
$senha      = $_POST['senha'];
$telefone = preg_replace('/\D/', '', $_POST['telefone']);
$senhaconfirm = $_POST["confirmar_senha"];
$acesso     = 0; 
$cpf = preg_replace('/\D/', '', $_POST['cpf']);
$hash = password_hash($senha, PASSWORD_DEFAULT); 

$sql_check_cpf = "SELECT COUNT(*) FROM alunos WHERE CPF = ?";
$sql_check_email = "SELECT COUNT(*) FROM alunos WHERE Email = ?";
$stmt_check = $conn->prepare($sql_check_cpf);

if ($stmt_check === false) {
    echo "Erro interno ao preparar a verificação: " . $conn->error;
    $conn->close();
    exit;
}

$stmt_check->bind_param("s", $cpf);
$stmt_check->execute();
$stmt_check->bind_result($cpf_count);
$stmt_check->fetch();
$stmt_check->close();

if ($cpf_count > 0) {
    header("Location: cadastro.php?erro=1");
    exit;
}

$stmt_email = $conn->prepare("SELECT COUNT(*) FROM alunos WHERE email = ?");
$stmt_email->bind_param("s", $email);
$stmt_email->execute();
$stmt_email->bind_result($email_count);
$stmt_email->fetch();
$stmt_email->close();

if ($email_count > 0) {
    header("Location: cadastro.php?erro=2");
    exit;
}

$stmt = $conn->prepare("INSERT INTO alunos (CPF, Nome, Email, Telefone, SenhaHash, acesso) VALUES (?, ?, ?, ?, ?, ?)");


$stmt->bind_param("sssssi", $cpf, $nome, $email, $telefone, $hash, $acesso);

if ($stmt->execute()) {
    header("Location: login.php?status=sucesso");
    exit; 

} else {
  
    echo "Erro ao criar conta: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>