<?php
require('conexao.php'); 

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$foto_perfil_url = $_SESSION['usuario']['foto'] ?? 'Mídias/perfil.png'; 
$aluno_cpf = null;

if (isset($_SESSION['usuario']) && isset($_SESSION['usuario']['cpf'])) {
    $aluno_cpf = $_SESSION['usuario']['cpf'];

    // 2. Tenta usar a foto que JÁ ESTÁ SALVA na sessão (Prioridade após o primeiro login)
    // Use o índice que você SALVOU (Ajuste 'usuario' ou 'cpf' conforme necessário)
    if (isset($_SESSION['usuario']['foto']) && !empty($_SESSION['usuario']['foto'])) {
        $foto_perfil_url = htmlspecialchars($_SESSION['usuario']['foto']);
    }

    // 3. SE a conexão for bem-sucedida, busca a foto mais atualizada do banco.
    // Isso garante que se a foto for alterada em outro lugar, a sessão será atualizada.
    if (isset($conn) && $conn) {
        $sql_busca = "SELECT Foto FROM alunos WHERE CPF = ?"; 
        
        if ($stmt_busca = $conn->prepare($sql_busca)) {
            $stmt_busca->bind_param("s", $aluno_cpf);
            $stmt_busca->execute();
            $stmt_busca->bind_result($foto_caminho_db);

            // 4. Se a foto for encontrada no DB, atualiza a variável e a sessão.
            if ($stmt_busca->fetch() && !empty($foto_caminho_db)) {
                $foto_perfil_url = htmlspecialchars($foto_caminho_db);
                
                // ATUALIZA A SESSÃO para que o próximo refresh use este valor
                // Garanta que você está usando o mesmo índice do passo 2
                $_SESSION['usuario']['foto'] = $foto_caminho_db; 
            }
            $stmt_busca->close();
        }
    } 
} 
?>