<?php
session_start();
require('conexao.php');

$usuario = $_SESSION['usuario'] ?? null;
if (!$usuario || empty($usuario['cpf'])) {
    die("Erro: Sessão inválida. Usuário não logado.");
}

$cpf = $usuario['cpf'];

$id_curso = isset($_POST['id_curso']) ? intval($_POST['id_curso']) : 0;
$metodo_pagamento = $_POST['metodo_pagamento'] ?? '';

$numero_cartao = $_POST['numero-cartao'] ?? null;
$validade = $_POST['validade-cartao'] ?? null;
$cvv = $_POST['cvv-cartao'] ?? null;

$cod_barras = $_POST['cod_barras'] ?? null;
$vencimento_boleto = $_POST['vencimento_boleto'] ?? null;

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

try {
    $conn->begin_transaction();

    $stmt = $conn->prepare("SELECT CPF FROM alunos WHERE CPF = ? LIMIT 1");
    $stmt->bind_param("s", $cpf);
    $stmt->execute();
    $res = $stmt->get_result();
    $stmt->close();

    if ($res->num_rows === 0) {

        $stmt = $conn->prepare("INSERT INTO alunos (CPF) VALUES (?)");
        $stmt->bind_param("s", $cpf);
        $stmt->execute();
        $stmt->close();
    }


    $stmt = $conn->prepare("SELECT id_curso_aluno FROM aluno_cursos WHERE cpf_aluno = ? AND id_curso = ?");
    $stmt->bind_param("si", $cpf, $id_curso);
    $stmt->execute();
    $res_ver = $stmt->get_result();
    $stmt->close();

    if ($res_ver->num_rows > 0) {
        throw new Exception("Curso já adquirido.");
    }

    $stmt = $conn->prepare("SELECT Preço, Preçoparce FROM cursos WHERE Id_curso = ?");
    $stmt->bind_param("i", $id_curso);
    $stmt->execute();
    $res_curso = $stmt->get_result();
    $stmt->close();

    if ($res_curso->num_rows === 0) {
        throw new Exception("Curso não encontrado.");
    }

    $curso = $res_curso->fetch_assoc();
    $valor_original = floatval($curso['Preço']);
    $valor_parceiro = floatval($curso['Preçoparce']);
    $valor_final = ($metodo_pagamento === 'pix' || $metodo_pagamento === 'boleto') 
                    ? $valor_parceiro * 0.9 
                    : $valor_original;

    $data_registro = date('Y-m-d H:i:s');

    $stmt = $conn->prepare("INSERT INTO pagamentos (cpf, hr) VALUES (?, ?)");
    $stmt->bind_param("ss", $cpf, $data_registro);
    $stmt->execute();
    $id_pagamento = $stmt->insert_id;
    $stmt->close();

  
    if ($metodo_pagamento === 'cartao') {
        $stmt = $conn->prepare("INSERT INTO pagamentos_cartao (id_pagamento, valor, cpf, itens, CVV, Validade) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idsiss", $id_pagamento, $valor_final, $cpf, $id_curso, $cvv, $validade);
        $stmt->execute();
        $stmt->close();
    } elseif ($metodo_pagamento === 'pix') {
        $valor_pix_int = intval(round($valor_final));
        $hora_pix = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO pagamentos_pix (id_pagamento, hr, valor, cpf, itens) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isisi", $id_pagamento, $hora_pix, $valor_pix_int, $cpf, $id_curso);
        $stmt->execute();
        $stmt->close();
    } elseif ($metodo_pagamento === 'boleto') {
        $codigo_barras = $cod_barras ?? hash('crc32', $id_pagamento . $cpf . time());
        $vencimento = $vencimento_boleto ?? date('Y-m-d', strtotime('+3 days'));
        $hora_boleto = date('Y-m-d H:i:s');
        $stmt = $conn->prepare("INSERT INTO pagamentos_boleto (id_cursos, valor, cpf, itens, codigo, vencimento, hr) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("idsisss", $id_curso, $valor_final, $cpf, $id_curso, $codigo_barras, $vencimento, $hora_boleto);
        $stmt->execute();
        $stmt->close();
    }

    
    $stmt = $conn->prepare("INSERT INTO aluno_cursos (cpf_aluno, id_curso, progresso) VALUES (?, ?, 0)");
    $stmt->bind_param("si", $cpf, $id_curso);
    $stmt->execute();
    $stmt->close();

    $conn->commit();
    header("Location: index.php?status=sucesso");
    exit();

} catch (Throwable $e) {
    $conn->rollback();
    die("Erro: " . htmlspecialchars($e->getMessage()));
}
