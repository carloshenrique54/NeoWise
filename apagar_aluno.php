<?php
include_once 'conexao.php';

if (!empty($_POST['CPF'])) {
    $cpf = $_POST['CPF'];

    $stmt = $conn->prepare("DELETE FROM alunos WHERE CPF = ?");
    $stmt->bind_param("s", $cpf);

    if ($stmt->execute()) {

        header("Location: usuariosadm.php");
        exit();
    } else {
        echo "Erro ao deletar usuário: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "Usuário não encontrado. Verifique se o CPF está sendo enviado pelo formulário.";
}
?>
