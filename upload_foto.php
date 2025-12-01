<?php
session_start();
header('Content-Type: application/json');
require('conexao.php'); 

if (!isset($_SESSION['usuario']) || !isset($_SESSION['usuario']['cpf'])) {
    echo json_encode(['sucesso' => false, 'erro' => 'Usuário não autenticado.']);
    exit;
}

$diretorio_upload = 'Mídias/fotos-perfil/';
if (!is_dir($diretorio_upload)) {
    mkdir($diretorio_upload, 0755, true); // cria o diretório se não existir
}

$aluno_cpf = $_SESSION['usuario']['cpf']; 
$nome_campo_arquivo = 'nova_foto'; 

if (isset($_FILES[$nome_campo_arquivo]) && $_FILES[$nome_campo_arquivo]['error'] === UPLOAD_ERR_OK) {
    
    $arquivo = $_FILES[$nome_campo_arquivo];
    $tipos_permitidos = ['image/jpeg', 'image/png', 'image/webp'];

    if (!in_array($arquivo['type'], $tipos_permitidos) || $arquivo['size'] > 5000000) {
        echo json_encode(['sucesso' => false, 'erro' => 'Formato inválido ou tamanho > 5MB.']);
        exit;
    }

    $extensao = pathinfo($arquivo['name'], PATHINFO_EXTENSION);
    $nome_arquivo = $aluno_cpf . '_' . time() . '.' . $extensao;
    $caminho_final_banco = $diretorio_upload . $nome_arquivo;

    if (move_uploaded_file($arquivo['tmp_name'], $caminho_final_banco)) {

        $sql = "UPDATE alunos SET Foto = ? WHERE CPF = ?";
        if ($stmt = $conn->prepare($sql)) {
            $stmt->bind_param("ss", $caminho_final_banco, $aluno_cpf);
            if ($stmt->execute()) {
                // Atualiza a sessão para manter a foto após recarregar
                $_SESSION['usuario']['foto'] = $caminho_final_banco;

                echo json_encode([
                    'sucesso' => true,
                    'mensagem' => 'Foto atualizada!',
                    'nova_url' => $caminho_final_banco
                ]);
            } else {
                echo json_encode(['sucesso' => false, 'erro' => 'Erro ao salvar a foto no banco.']);
            }
            $stmt->close();
        } else {
            echo json_encode(['sucesso' => false, 'erro' => 'Erro ao preparar consulta no banco.']);
        }
        $conn->close();

    } else {
        echo json_encode(['sucesso' => false, 'erro' => 'Falha ao mover o arquivo.']);
    }

} else {
    echo json_encode(['sucesso' => false, 'erro' => 'Nenhum arquivo enviado.']);
}
?>
