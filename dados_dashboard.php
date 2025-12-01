<?php
require ('conexao.php');

// pega o filtro da URL (semana, mes, ano)
$filtro = $_GET['filtro'] ?? 'mes';

switch ($filtro) {
    case 'semana':
        $condicao = "YEARWEEK(data_pagamento, 1) = YEARWEEK(CURDATE(), 1)";
        $condicao_usuarios = "YEARWEEK(data_cadastro, 1) = YEARWEEK(CURDATE(), 1)";
        break;
    case 'ano':
        $condicao = "YEAR(data_pagamento) = YEAR(CURDATE())";
        $condicao_usuarios = "YEAR(data_cadastro) = YEAR(CURDATE())";
        break;
    default:
        $condicao = "MONTH(data_pagamento) = MONTH(CURDATE()) AND YEAR(data_pagamento) = YEAR(CURDATE())";
        $condicao_usuarios = "MONTH(data_cadastro) = MONTH(CURDATE()) AND YEAR(data_cadastro) = YEAR(CURDATE())";
        break;
}

// ganhos
$sql_ganhos = "SELECT SUM(valor) AS total FROM pagamentos WHERE $condicao";
$result_ganhos = $conn->query($sql_ganhos)->fetch_assoc()['total'] ?? 0;

// novos usuários
$sql_usuarios = "SELECT COUNT(*) AS total FROM alunos WHERE $condicao_usuarios";
$result_usuarios = $conn->query($sql_usuarios)->fetch_assoc()['total'] ?? 0;

// retorna JSON limpo
header('Content-Type: application/json');
echo json_encode([
    "ganhos" => $result_ganhos,
    "usuarios" => $result_usuarios
]);