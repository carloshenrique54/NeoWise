<?php
$servername = "localhost";
$username   = "root";
$password   = "";
$database   = "db_finwise";

$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    http_response_code(500); 
    
    echo json_encode([
        'sucesso' => false, 
        'erro' => 'Erro de servidor: Falha na conexão com o banco de dados.'
    ]);
    
    exit(); 
}