<?php
session_start();
require('conexao.php'); 

$kpis = [];

// --- 1. CONFIGURAÇÃO DE VARIÁVEIS DE SESSÃO (Mantendo minúsculas para resolver o problema anterior) ---
$foto_perfil_url = $_SESSION['usuario']['foto'] ?? 'Mídias/perfil.png'; 
$email = $_SESSION['usuario']['email'] ?? '';
$nome_completo = $_SESSION['usuario']['nome'] ?? ''; 
$telefone = $_SESSION['usuario']['telefone'] ?? '';

$partes_nome = explode(' ', $nome_completo);
$primeiro_nome = htmlspecialchars($partes_nome[0] ?? ''); 

// --- 2. QUERY: Receita Total (OK) ---
$sql_receita = "
    SELECT 
        SUM(t.total_valor) AS ReceitaTotal 
    FROM (
        SELECT valor AS total_valor FROM pagamentos_cartao
        UNION ALL
        SELECT valor FROM pagamentos_boleto
        UNION ALL
        SELECT CAST(valor AS DECIMAL(10, 2)) FROM pagamentos_pix
    ) t
";

$result_receita = $conn->query($sql_receita);
$kpis['receita'] = $result_receita->fetch_assoc();


// --- 3. QUERY: Métodos de Pagamento (OK) ---
$sql_metodos = "
    SELECT 'Cartão' AS Metodo, COUNT(*) AS Total FROM pagamentos_cartao
    UNION ALL
    SELECT 'Boleto', COUNT(*) FROM pagamentos_boleto
    UNION ALL
    SELECT 'PIX', COUNT(*) FROM pagamentos_pix
";
$result_metodos = $conn->query($sql_metodos);
$kpis['metodos'] = $result_metodos->fetch_all(MYSQLI_ASSOC);


// --- 4. QUERY: Vendas por Categoria (COM TRATAMENTO DE ERRO) ---
$sql_vendas_categoria = "
    SELECT 
        C.Categoria, 
        COUNT(AC.id_curso) AS TotalVendas
    FROM aluno_cursos AC
    JOIN cursos C ON AC.id_curso = C.Id_curso
    GROUP BY C.Categoria
    ORDER BY TotalVendas DESC
";
$result_vendas_categoria = $conn->query($sql_vendas_categoria);

if (!$result_vendas_categoria) {
    // A query falhou. Cria dados de fallback que exibirão o erro no gráfico.
    $errorMessage = "ERRO SQL: " . $conn->error . " (Verifique as tabelas aluno_cursos e cursos)";
    $kpis['vendas_categoria'] = [
        ['Categoria' => $errorMessage, 'TotalVendas' => 1]
    ];
} else {
    $vendas_data = $result_vendas_categoria->fetch_all(MYSQLI_ASSOC);
    
    if (empty($vendas_data)) {
        // A query rodou, mas retornou 0 resultados.
        $kpis['vendas_categoria'] = [
            ['Categoria' => "Sem vendas registradas em 'aluno_cursos'", 'TotalVendas' => 1]
        ];
    } else {
        $kpis['vendas_categoria'] = $vendas_data;
    }
}


// --- 5. QUERY: Alunos Totais (OK) ---
$sql_alunos = "SELECT COUNT(CPF) AS TotalAlunos FROM alunos";
$result_alunos = $conn->query($sql_alunos);
$kpis['alunos'] = $result_alunos->fetch_assoc();


// --- 6. QUERY: Curso Mais Vendido (COM TRATAMENTO DE ERRO) ---
$sql_top_curso = "
    SELECT C.Nome, COUNT(AC.id_curso) AS TotalVendas
    FROM aluno_cursos AC
    JOIN cursos C ON AC.id_curso = C.Id_curso
    GROUP BY C.Nome
    ORDER BY TotalVendas DESC
    LIMIT 1
";
$result_top_curso = $conn->query($sql_top_curso);

if (!$result_top_curso) {
    $kpis['top_curso'] = ['Nome' => "ERRO: " . $conn->error, 'TotalVendas' => 0];
} else {
    $kpis['top_curso'] = $result_top_curso->fetch_assoc();
    if (empty($kpis['top_curso'])) {
        $kpis['top_curso'] = ['Nome' => "Nenhum curso vendido", 'TotalVendas' => 0];
    }
}


// --- 7. QUERY: Acesso de Alunos (OK) ---
$sql_acesso = "
    SELECT 
        acesso, 
        COUNT(CPF) AS Total
    FROM alunos
    GROUP BY acesso
    ORDER BY acesso DESC
";
$result_acesso = $conn->query($sql_acesso);
$kpis['acesso'] = $result_acesso->fetch_all(MYSQLI_ASSOC);


// --- 8. QUERY: Mensagens no Fórum (OK) ---
$sql_forum_msg = "SELECT COUNT(idmensagem) AS TotalMensagens FROM forum";
$result_forum_msg = $conn->query($sql_forum_msg);
$kpis['mensagens_forum'] = $result_forum_msg->fetch_assoc();


// --- 9. QUERY: Tópico Mais Popular (OK) ---
$sql_topico_likes = "
    SELECT topico, likes
    FROM forum
    ORDER BY likes DESC
    LIMIT 1
";
$result_topico_likes = $conn->query($sql_topico_likes);
$kpis['topico_popular'] = $result_topico_likes->fetch_assoc();

?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="icon" href="Mídias/Logo branca.png">

    <style>

        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .dashboard-container { max-width: 1200px; margin: auto;}
        h1 { color: #E6C72B; text-align: center; margin-bottom: 30px; display: flex;}
        .kpi-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 40px; }
        .kpi-card { background-color: #333; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1) ; }
        .kpi-card h3 { margin-top: 0; color: white; font-size: 16px; }
        /* Estilo para destacar a cor em caso de erro/aviso */
        .kpi-card p { font-size: 28px; font-weight: bold; color: #2bbf36; }
        .kpi-card .warning { color: #ffc107; font-size: 16px; font-weight: bold;} 

        .section-title { border-bottom: 2px solid #ccc; padding-bottom: 10px; margin-top: 40px; margin-bottom: 20px; color: white; }
        .chart-row { display: flex; gap: 20px; margin-bottom: 40px; }
        .chart-container { flex: 1; background-color: #333;; padding: 20px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0,0,0,0.1); max-width: 600px; }
    </style>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="dashboard.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
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
                  header('Location: login.php');
                  exit();
              } 
              elseif ($_SESSION['usuario']['acesso'] != 2) {
                  echo '<a href="perfiladm.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . $primeiro_nome . '</h3>'. '</li></a>';
                  echo '<img src="' . htmlspecialchars($foto_perfil_url) . '" alt="Foto de Perfil" class="foto-perfil">';
              } 
              else {
                  echo '<a href="perfil.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . $primeiro_nome . '</h3>'. '</li></a>';
                  echo '<img src="' . htmlspecialchars($foto_perfil_url) . '" alt="Foto de Perfil" class="foto-perfil">';
              }
              ?>
          </ul>
      </nav>
  </header>

<div class="dashboard-container">
    <div id="dashboard-title">
        <a href="perfiladm.php">Voltar</a>
        <h1>Painel de Controle Finwise</h1>
    </div>
    <div class="kpi-grid">
        <div class="kpi-card">
            <h3>Receita Total</h3>
            <p>R$ <?php echo number_format($kpis['receita']['ReceitaTotal'] ?? 0, 2, ',', '.'); ?></p>
        </div>
        <div class="kpi-card">
            <h3>Alunos Cadastrados</h3>
            <p><?php echo $kpis['alunos']['TotalAlunos'] ?? 0; ?></p>
        </div>
        <div class="kpi-card">
            <h3>Total de Mensagens no Fórum</h3>
            <p><?php echo $kpis['mensagens_forum']['TotalMensagens'] ?? 0; ?></p>
        </div>
        <div class="kpi-card">
            <h3>Curso Mais Vendido</h3>
            <?php if (strpos($kpis['top_curso']['Nome'] ?? '', 'ERRO') !== false): ?>
                 <p class="warning"><?php echo htmlspecialchars($kpis['top_curso']['Nome']); ?></p>
            <?php else: ?>
                 <p><?php echo htmlspecialchars($kpis['top_curso']['Nome'] ?? 'N/A'); ?></p>
            <?php endif; ?>
           
        </div>
    </div>

    <h2 class="section-title">Análise de Vendas e Usuários</h2>
    
    <div class="chart-row">
        <div class="chart-container">
            <h3>Distribuição de Vendas por Categoria</h3>
            <canvas id="vendasCategoriaChart"></canvas>
        </div>

        <div class="chart-container">
            <h3>Alunos por Nível de Acesso</h3>
            <canvas id="acessoChart"></canvas>
        </div>
    </div>
    
    <h2 class="section-title">Análise de Engajamento</h2>
    
    <div class="kpi-card" style="margin-top: 20px;">
        <h3>Tópico do Fórum Mais Popular (por Likes)</h3>
        <?php if ($kpis['topico_popular']): ?>
            <p style="color: #28a745;"><?php echo htmlspecialchars($kpis['topico_popular']['topico']); ?></p>
            <h3>Likes: <?php echo $kpis['topico_popular']['likes']; ?></h3><i class="fa-solid fa-thumbs-up"></i>
        <?php else: ?>
            <p>Nenhum tópico encontrado.</p>
        <?php endif; ?>
    </div>

    <h2 class="section-title">Posts no fórum</h2>

<div class="kpi-card">
    <h3>Posts Recentes</h3>
    <?php
    if (!$conn) {
        die("Erro na conexão com o banco.");
    }

    $sql = "SELECT idmensagem, cpfmensagem, mensagem, hr, topico, likes
            FROM forum
            ORDER BY hr DESC
            LIMIT 10";
    $res = $conn->query($sql);

    if ($res && $res->num_rows > 0):
        while ($post = $res->fetch_assoc()):
    ?>
        <div class="mensagemss"">
            <h4><strong>Tópico:</strong> <?php echo htmlspecialchars($post['topico']); ?></h4>
            <h4><strong>Autor (CPF):</strong> <?php echo htmlspecialchars($post['cpfmensagem']); ?></h4>
            <h4>Mensagem:</strong> <?php echo htmlspecialchars($post['mensagem']); ?></p>
            <h4><strong>Data/Hora:</strong> <?php echo date('d/m/Y H:i', strtotime($post['hr'])); ?></h4>
            <h4><strong>Likes:</strong> <?php echo $post['likes']; ?></h4>

            <form method="post" action="deletar_post.php" onsubmit="return confirm('Tem certeza que deseja deletar este post?');">
                <input type="hidden" name="idmensagem" value="<?php echo $post['idmensagem']; ?>">
                <button type="submit">Deletar</button>
            </form>
        </div>
    <?php
        endwhile;
    else:
    ?>
        <p>Nenhum post encontrado.</p>
    <?php endif; ?>
</div>

</div>

<script>
    
    // --- DADOS DA CATEGORIA ---
    const dadosVendasCategoriaPHP = <?php echo json_encode($kpis['vendas_categoria']); ?>;
    const labelsVendas = [];
    const dataVendas = [];
    
    dadosVendasCategoriaPHP.forEach(item => {
        labelsVendas.push(item.Categoria); 
        dataVendas.push(item.TotalVendas);
    });

    const ctxVendas = document.getElementById('vendasCategoriaChart').getContext('2d');
    
    new Chart(ctxVendas, {
        type: 'pie',
        data: {
            labels: labelsVendas, 
            datasets: [{
                label: 'Vendas por Categoria',
                data: dataVendas,
                
                // Mudei as cores para que a primeira cor (erro/vazio) seja diferente
                backgroundColor: [
                    '#ffc107', '#007bff', '#28a745', '#dc3545', '#6f42c1', '#17a2b8' 
                ],
                borderColor: '#fff',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            plugins: {
                legend: { position: 'top' },
                title: {
                    display: true,
                    text: 'Vendas de Cursos por Categoria'
                }
            }
        }
    });

    
    // --- DADOS DE ACESSO ---
    const dadosAcessoPHP = <?php echo json_encode($kpis['acesso']); ?>;
    const labelsAcesso = [];
    const dataAcesso = [];

    dadosAcessoPHP.forEach(item => {
        let label = item.acesso === '1' ? 'Alunos Ativos' : 
                     (item.acesso === '0' ? 'Cadastrados (Não Pagantes)' : 'Outros');
        labelsAcesso.push(label);
        dataAcesso.push(item.Total);
    });
    
    const ctxAcesso = document.getElementById('acessoChart').getContext('2d');
    
    new Chart(ctxAcesso, {
        type: 'bar',
        data: {
            labels: labelsAcesso,
            datasets: [{
                label: 'Total de Alunos',
                data: dataAcesso, 
                backgroundColor: [
                    'rgba(23, 162, 184, 0.8)',
                    'rgba(220, 53, 69, 0.8)'
                ],
                borderColor: [
                    'rgba(23, 162, 184, 1)',
                    'rgba(220, 53, 69, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Contagem de CPFs'
                    }
                }
            },
            plugins: {
                legend: { display: false },
                title: {
                    display: true,
                    text: 'Distribuição de Alunos por Nível'
                }
            }
        }
    });
</script>
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
</body>
</html>