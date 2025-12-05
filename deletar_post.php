<?php
require 'conexao.php';

if (isset($_POST['idmensagem'])) {
    $id = intval($_POST['idmensagem']);

    $sql = "DELETE FROM forum WHERE idmensagem = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: dashboard.php"); // volta pro dashboard
        exit;
    } else {
        echo "Erro ao deletar post.";
    }
} else {
    echo "Post não informado.";
}
?>
