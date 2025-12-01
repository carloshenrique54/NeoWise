<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

session_start();

require('foto-perfil.php');
require('conexao.php');

$curso_id = filter_input(INPUT_GET, 'curso_id', FILTER_VALIDATE_INT);
$curso_detalhes = null;

if ($curso_id) {
    $sql = "SELECT Id_curso, Nome, Imagem, `Preço` AS Preco, `Preçoparce` AS PrecoParce FROM cursos WHERE Id_curso = ?";
    
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $curso_id);
        if ($stmt->execute()) {
            $resultado = $stmt->get_result();
            if ($resultado->num_rows === 1) {
                $curso_detalhes = $resultado->fetch_assoc();
            }
        }
        $stmt->close();
    }
}
$conn->close();

if (!$curso_detalhes) {
    header('Location: cursos.php?erro=curso_nao_encontrado');
    exit;
}

$id_curso_compra   = htmlspecialchars($curso_detalhes['Id_curso']);
$nome_curso_compra = htmlspecialchars($curso_detalhes['Nome']);
$preco_curso_compra = number_format($curso_detalhes['Preco'], 2, ',', '.');
$preco_parcelado    = number_format($curso_detalhes['PrecoParce'], 2, ',', '.');
$imagem_curso_compra = htmlspecialchars($curso_detalhes['Imagem']);
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pagamento</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="pagamento.css">
    <link rel="icon" href="Mídias/icone.ico">
</head>
<body>
<header>
    <div id="logo">
        <img src="Mídias/Logo branca.png" alt="Logo da FinWise" />
    </div>
    <nav>
        <ul>
            <li><a id="home" href="index.php">Home</a></li>
            <li><a id="cursos" href="cursos.php">Cursos</a></li>
            <li><a id="sobre" href="sobre.php">Sobre</a></li>
            <li><a id="contato" href="contato.php">Contato</a></li>
            <li><a id="forum" href="forum.php">Fórum</a></li>
        </ul>
        <ul>
            <?php
            if (!isset($_SESSION['usuario'])) {
                echo '<li><a href="login.php">Login</a></li>';
                echo '<li><a id="cadastro" href="cadastro.php">Cadastre-se</a></li>';
            } else {
                $nome_completo = $_SESSION['usuario']['nome'];
                $partes_nome = explode(' ', $nome_completo);
                $primeiro_nome = $partes_nome[0];

                if ($_SESSION['usuario']['acesso'] == 0) {
                    echo '<a href="perfil.php"><li id="usuario_nome">Olá, <h3>' . htmlspecialchars($primeiro_nome) . '</h3></li></a>';
                } else {
                    echo '<a href="perfiladm.php"><li id="usuario_nome">Olá, <h3>' . htmlspecialchars($primeiro_nome) . '</h3></li></a>';
                }

                echo '<img src="' . htmlspecialchars($foto_perfil_url) . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>

<main>
    <form id="card1" action="pagamento_processar.php" method="POST" id="form-pagamento">
        <h2>Preencha as informações:</h2>
        
        <input type="hidden" name="id_curso" value="<?php echo $id_curso_compra; ?>">

        <label for="nome">Nome no Cartão:</label>
        <input type="text" id="nome" name="nome" value="<?php echo isset($_SESSION['usuario']['nome']) ? htmlspecialchars($_SESSION['usuario']['nome']) : ''; ?>" required>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" value="<?php echo isset($_SESSION['usuario']['email']) ? htmlspecialchars($_SESSION['usuario']['email']) : ''; ?>" required>

        <label for="telefone">Telefone:</label>
        <input type="tel" id="telefone" name="telefone" required>

        <label for="cpf">CPF:</label>
        <input type="text" id="cpf" name="cpf" required>

        <div id="pagamento">
            <h3>Escolha o Método de Pagamento:</h3>
            <div id="metodos-pagamento-cards"> 
                <input type="radio" id="cartao" name="metodo_pagamento" value="cartao" required>
                <label for="cartao" class="metodo-card">
                    <div class="card-conteudo">
                        <span class="card-icone cartao-icone">💳</span> 
                        <span class="card-texto">Cartão de Crédito</span>
                    </div>
                </label>

                <input type="radio" id="pix" name="metodo_pagamento" value="pix">
                <label for="pix" class="metodo-card">
                    <div class="card-conteudo">
                        <span class="card-icone pix-icone">❖</span> 
                        <span class="card-texto">PIX</span>
                    </div>
                    <span class="card-desconto">desconto de 10%</span>
                </label>

                <input type="radio" id="boleto" name="metodo_pagamento" value="boleto">
                <label for="boleto" class="metodo-card">
                    <div class="card-conteudo">
                        <span class="card-icone boleto-icone">🧾</span> 
                        <span class="card-texto">Boleto</span>
                    </div>
                    <span class="card-desconto">desconto de 10%</span>
                </label>
            </div>

            <div id="Pagamento-cartao">
                <label for="numero-cartao">Número do Cartão:</label>
                <input type="text" id="numero-cartao" name="numero-cartao">

                <label for="validade-cartao">Validade:</label>
                <input type="text" id="validade-cartao" name="validade-cartao">

                <label for="cvv-cartao">CVV:</label>
                <input type="text" id="cvv-cartao" name="cvv-cartao">
            </div>

            <div id="Pagamento-pix">
                <img src="https://desenvolvedores.cielo.com.br/api-portal/sites/default/files/images/qrcodemodeloantigo.PNG" alt="">
                <p>Faça o pagamento via PIX utilizando o QR Code acima.</p>
            </div>

            <div id="Pagamento-boleto">
                <h2>Pagamento via Boleto</h2>
                <label for="nome-boleto">Nome Completo:</label>
                <input type="text" id="nome-boleto" name="nome-boleto" placeholder="Seu nome">

                <label for="email-boleto">E-mail:</label>
                <input type="email" id="email-boleto" name="email-boleto" placeholder="seu@email.com">

                <div class="info-boleto">
                    Após clicar em <strong>Finalizar Pagamento</strong>, será gerado um boleto com código de pagamento que você poderá usar no seu banco ou app.
                </div>
            </div>
        </div>

        <button id="btn-pagamento" type="submit">Finalizar Pagamento</button>
        <span id="mensagem"></span>
    </form>

    <div id="resumo-compra">
        <h2>Resumo da Compra</h2>
        <div id="curso">
            <img id="imagem-curso" src="Mídias/Cursos/<?php echo $imagem_curso_compra; ?>" alt="Imagem do curso: <?php echo $nome_curso_compra; ?>">
            <p>Curso: <span id="nome-curso"><?php echo $nome_curso_compra; ?></span></p>
            <p>Preço: <span id="preco-curso">R$ <?php echo $preco_curso_compra; ?></span></p>
            <p>Preço parcelado: <span id="preco-parcelado">R$ <?php echo $preco_parcelado; ?></span></p>
        </div>
    </div>
</main>

<footer>
    <div id="footer-content">
        <div class="footer-section">
            <h3>FinWise</h3>
            <p>Sua plataforma de cursos online para se tornar um profissional de destaque no mercado.</p>
        </div>
        <div class="footer-section">
            <h3>Links Rápidos</h3>
            <ul>
                <li><a href="index.php">Home</a></li>
                <li><a href="cursos.php">Cursos</a></li>
                <li><a href="sobre.php">Sobre</a></li>
                <li><a href="contato.php">Contato</a></li>
            </ul>
        </div>
        <div class="footer-section">
            <h3>Contato</h3>
            <p>Email: contato@neowise.com.br</p>
            <p>Telefone: (11) 1234-5678</p>
            <p>Instagram: @neowise</p>
        </div>
    </div>
</footer>

<script src="tema.js"></script>
<script src="pagamento.js"></script>
</body>
</html>
