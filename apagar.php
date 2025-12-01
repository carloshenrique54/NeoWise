<?php
include_once 'conexao.php';

if (isset($_POST['id'])) {
    $id = intval($_POST['id']); 

    // Checar se existem alunos matriculados
    $result_alunos = $conn->query("SELECT COUNT(*) AS total FROM aluno_cursos WHERE id_curso = $id");
    $alunos = $result_alunos->fetch_assoc()['total'];

    // Checar se existem pagamentos
    $result_pag = $conn->query("SELECT COUNT(*) AS total FROM pagamentos_pix WHERE itens = $id");
    $pagamentos = $result_pag->fetch_assoc()['total'];

    if ($alunos > 0 || $pagamentos > 0) {
        echo "Não é possível deletar este curso porque ";
        if ($alunos > 0) echo "existem alunos matriculados. ";
        if ($pagamentos > 0) echo "existem pagamentos registrados.";
        exit();
    }

    // Se não houver dependências, apagar o curso
    if ($conn->query("DELETE FROM cursos WHERE Id_curso = $id") === TRUE) {
        header("Location: adicionar.php"); 
        exit();
    } else {
        echo "Erro ao deletar curso: " . $conn->error;
    }

} else {
    echo "ID do curso não informado!";
}
?>