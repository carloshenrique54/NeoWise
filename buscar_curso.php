<?php
require('conexao.php'); 

header('Content-Type: application/json');

$cursoId = $_GET['id'] ?? null;

if (!$cursoId) {
    http_response_code(400);
    echo json_encode(['error' => 'ID do curso não fornecido.']);
    exit;
}

// 1. Converte o ID recebido (que é uma string) para inteiro
$cursoIdNumerico = intval($cursoId); 


$stmt = $conn->prepare("
    SELECT 
        titulo, 
        descricao, 
        imagem, 
        preco, 
        preco_parcelado, 
        conteudo, 
        atividades, 
        beneficios
    FROM 
        cursos 
    WHERE 
        id_curso = ?
");

// 2. MUDANÇA CRUCIAL: 'i' para INTEGER
$stmt->bind_param("i", $cursoIdNumerico); 
$stmt->execute();
$resultado = $stmt->get_result();

if ($resultado->num_rows > 0) {
    // ... (O restante do código de processamento do JSON permanece o mesmo)
    $curso = $resultado->fetch_assoc();
    
    $curso['conteudo'] = array_filter(array_map('trim', explode(',', $curso['conteudo'])));
    $curso['atividades'] = array_filter(array_map('trim', explode(',', $curso['atividades'])));
    $curso['beneficios'] = array_filter(array_map('trim', explode(',', $curso['beneficios'])));
    
    http_response_code(200);
    echo json_encode($curso);
} else {
    http_response_code(404);
    echo json_encode(['error' => 'Curso com ID ' . htmlspecialchars($cursoId) . ' não encontrado.']);
}

$stmt->close();
$conn->close();
?>