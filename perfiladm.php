<?php

require ('conexao.php');
require ('auth.php');
require ('foto-perfil.php');

// --- Função de Segurança: Validação do Filtro ---
function validarFiltro($filtro) {
    $filtros_validos = ['semana', 'mes', 'ano'];
    return in_array($filtro, $filtros_validos) ? $filtro : 'mes';
}


// --- 1. CORREÇÃO CRÍTICA: Revertendo as chaves de sessão para MINÚSCULAS ---
// Isso resolve o problema de informações do perfil que não carregam.
$foto_perfil_url = $_SESSION['usuario']['foto'] ?? 'Mídias/perfil.png'; 
$email = $_SESSION['usuario']['email'] ?? '';
$nome_completo = $_SESSION['usuario']['nome'] ?? ''; // Usando $nome_completo para consistência
$telefone = $_SESSION['usuario']['telefone'] ?? '';

// Extração do primeiro nome
$partes_nome = explode(' ', $nome_completo);
$primeiro_nome = htmlspecialchars($partes_nome[0] ?? ''); 


// --- 2. TOTAIS GERAIS (Global) - Otimizado com COUNT(*) ---
$sql_alunos = "SELECT COUNT(*) AS total FROM alunos";
$total_alunos = $conn->query($sql_alunos)->fetch_assoc()['total'] ?? 0;

$sql_cursos = "SELECT COUNT(*) AS total FROM cursos";
$total_cursos = $conn->query($sql_cursos)->fetch_assoc()['total'] ?? 0;

$sql_vendas = "SELECT COUNT(*) AS total FROM pagamentos";
$total_vendas = $conn->query($sql_vendas)->fetch_assoc()['total'] ?? 0;


// --- 3. LÓGICA DE FILTRO (Por tempo) ---
$filtro = validarFiltro($_GET['filtro'] ?? 'mes');

// DEFINIÇÃO DAS CONDIÇÕES (As condições para pagamento usam o alias 'p.hr' da tabela 'pagamentos')
switch ($filtro) {
    case 'semana':
        $condicao_user = "YEARWEEK(data_cadastro, 1) = YEARWEEK(CURDATE(), 1)";
        $condicao_pag  = "YEARWEEK(p.hr, 1) = YEARWEEK(CURDATE(), 1)";
        break;

    case 'ano':
        $condicao_user = "YEAR(data_cadastro) = YEAR(CURDATE())";
        $condicao_pag  = "YEAR(p.hr) = YEAR(CURDATE())";
        break;

    default: // mes
        $condicao_user = "MONTH(data_cadastro) = MONTH(CURDATE()) AND YEAR(data_cadastro) = YEAR(CURDATE())";
        $condicao_pag  = "MONTH(p.hr) = MONTH(CURDATE()) AND YEAR(p.hr) = YEAR(CURDATE())";
        break;
}

// Variáveis para manter o SELECT na opção correta
$selected_semana = ($filtro === 'semana') ? 'selected' : '';
$selected_mes = ($filtro === 'mes') ? 'selected' : '';
$selected_ano = ($filtro === 'ano') ? 'selected' : '';


// --- 4. NOVOS USUÁRIOS (Filtrado) ---
$sql_novos_usuarios = "SELECT COUNT(*) AS total FROM alunos WHERE $condicao_user AND data_cadastro IS NOT NULL";
$result = $conn->query($sql_novos_usuarios);
$novos_usuarios = $result->fetch_assoc()['total'] ?? 0;


// --- 5. GANHOS (CRITICAL FIX: JOIN com a tabela 'pagamentos' para obter 'hr') ---
$sql_ganhos = "
    SELECT 
        (
            -- Soma PIX
            SELECT IFNULL(SUM(pp.valor), 0) 
            FROM pagamentos_pix pp 
            JOIN pagamentos p ON pp.id_pagamento = p.id_compra 
            WHERE $condicao_pag
        )
        +
        (
            -- Soma CARTAO
            SELECT IFNULL(SUM(pc.valor), 0) 
            FROM pagamentos_cartao pc 
            JOIN pagamentos p ON pc.id_pagamento = p.id_compra 
            WHERE $condicao_pag
        )
        +
        (
            -- Soma BOLETO (JOIN por CPF)
            SELECT IFNULL(SUM(pb.valor), 0) 
            FROM pagamentos_boleto pb 
            JOIN pagamentos p ON pb.cpf = p.cpf
            WHERE $condicao_pag
        )
    AS total;
";
$result = $conn->query($sql_ganhos);
$ganhos = $result->fetch_assoc()['total'] ?? 0;

// Formatação para R$
$ganhos_formatado = number_format((float)$ganhos, 2, ',', '.');
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Perfil administrador</title>
  <link rel="stylesheet" href="header.css">
  <link rel="stylesheet" href="perfiladm.css">
  <link rel="icon" href="Mídias/icone.ico">
</head>
<body>
  <header>
    <div id="logo">
        <img src="Mídias/Logo branca.png" alt="Logo da FinWise" />
    </div>
    <nav>
        <ul>
            <li><a id="home" href="index.php">Ínicio</a></li>
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
                $primeiro_nome = htmlspecialchars($partes_nome[0]);
                echo '<a href="perfil.php"><li id="usuario_nome">Olá, <h3>' . $primeiro_nome . '</h3></li></a>';
                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>

  <main class="perfil-container">
    <aside class="perfil-lateral">
      <div class="perfil-header">
        <input type="file" id="upload-foto-perfil" accept="image/*" style="display: none;">
        <div class="perfil-foto-wrapper clicavel" id="botao-foto-perfil">
          <img src="<?php echo htmlspecialchars($foto_perfil_url); ?>" alt="Foto de Perfil" class="perfil-foto">
          <div class="overlay-editar">Trocar Foto</div>
        </div>

        <h3 class="perfil-nome"><?php echo $primeiro_nome ?></h3>
        <p class="perfil-email"><?php echo htmlspecialchars($email); ?></p>
      </div>

      <div class="perfil-info">
        <h4>Informações de login:</h4>
        <p>E-mail: <span><?php echo htmlspecialchars($email); ?></span></p>
        <p>Telefone: <span><?php echo htmlspecialchars($telefone); ?></span></p>

        <form action="alterardados.php" method="POST">
            <button type="submit" class="btn-alterar-dados">Alterar Dados</button>
        </form>
        <a href="logout.php" id="btn-logout">Sair</a>

        <div id="acoes-adm">
          <a href="adicionar.php" id="addcurso">Gerenciar cursos</a>
          <a href="usuariosadm.php" id="gerenciarusuarios">Gerenciar Usuários</a>
          <a href="criarprova.php" id="gerenciarusuarios">Gerenciar Provas</a>
        </div>
      </div>

      <div class="fundo-tecnologico"></div>
    </aside>

    <section class="perfil-principal">
        <div id="card-dashboard">
          <div class="dashboard-item">
            <h2>Total de Usuários</h2>
            <?php echo $total_alunos; ?>
            <h2>Total de Cursos</h2>
            <?php echo $total_cursos; ?>
            <h2>Total de vendas</h2>
            <?php echo $total_vendas; ?>
          </div>

          <div class="dashboard-item">
            <h2>Mensagem dos usuários</h2>
            <a href="responder.php">Ver mais</a>
          </div>

          <div class="dashboard-item">
              <div class="top-dashboard">
                <h2>Filtrar por:</h2>
                <select id="filtroTempo" onchange="mudarFiltro(this)">
                  <option value="semana" <?php echo $selected_semana; ?>>Última semana</option>
                  <option value="mes" <?php echo $selected_mes; ?>>Este mês</option>
                  <option value="ano" <?php echo $selected_ano; ?>>Este ano</option>
                </select>
              </div>

              <div id="resultados">
                  <h3>Ganhos: R$ <span id="ganhos"><?php echo $ganhos_formatado; ?></span></h3>
                  <h3>Novos Usuários: <span id="usuarios"><?php echo $novos_usuarios; ?></span></h3>
              </div>
          </div>

          <div class="dashboard-item">
                <h2>Ir para o DashBoard Completo</h2>
                <a href="dashboard.php">Ver mais</a>
          </div>
        </div>
    </section>
  </main>

  <footer>
      <div id="footer-content">
          <div class="footer-section">
              <h3>NeoWise</h3>
              <p>Sua plataforma de cursos online para se tornar um profissional de destaque no mercado.</p>
          </div>
          <div class="footer-section">
              <h3>Links Rápidos</h3>
              <ul>
                  <li><a href="index.php">Ínicio</a></li>
                  <li><a href="cursos.php">Cursos</a></li>
                  <li><a href="sobre.php">Sobre</a></li>
                  <li><a href="contato.php">Contato</a></li>
                  <li><a href="forum.php">Fórum</a></li>
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

  <script>
  function mudarFiltro(sel) {
      window.location = "?filtro=" + sel.value;
  }
  </script>

  <script src="tema.js"></script>
  <script src="perfil.js"></script>
  <script src="perfiladm.js"></script>
</body>
</html>