<?php
session_start();
require 'conexao.php';
require 'foto-perfil.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['acesso'] != 1) {
    header("Location: index.php");
    exit();
}

function esc($v) {
    return htmlspecialchars($v ?? '', ENT_QUOTES, 'UTF-8');
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function flash_set($msg) {
    $_SESSION['flash_msg'] = $msg;
}
function flash_get() {
    $m = $_SESSION['flash_msg'] ?? null;
    unset($_SESSION['flash_msg']);
    return $m;
}

function mapCategoriaProva($cursoCategoria) {
    $c = trim(mb_strtolower($cursoCategoria ?? ''));
    switch ($c) {
        case 'programação web':
        case 'programacao web':
            return 'web_dev';
        case 'programação':
        case 'programacao':
            return 'dev';
        case 'marketing digital':
            return 'marketing';
        default:
            return 'geral';
    }
}

$action = $_GET['action'] ?? 'list';

if ($action === 'list') {
    $sql = "SELECT p.*, c.Nome AS curso_nome, c.Categoria AS curso_categoria
            FROM provas p
            LEFT JOIN cursos c ON c.Id_curso = p.id_curso
            ORDER BY p.criada_em DESC, p.id DESC";
    $res = $conn->query($sql);

    $flash = flash_get();
    ?>
    <!DOCTYPE html>
    <html>
      <head>
        <meta charset="utf-8">
        <title>NeoWise</title>
        <link rel="stylesheet" href="header.css">
        <link rel="stylesheet" href="criarprova.css">
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
                    echo '<a href="perfil.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                } else {
                    echo '<a href="perfiladm.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                }

                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>
    <h1>Painel de Provas</h1>
    <?php if ($flash): ?><div class="flash"><?= esc($flash) ?></div><?php endif; ?>
    <p>
      <a class="btn" href="criarprova.php?action=form">+ Nova Prova</a>
      <a class="btn" href="index.php">Voltar ao site</a>
    </p>

    <table>
      <thead>
        <tr><th>ID</th><th>Título</th><th>Curso</th><th>Categoria (lógica)</th><th>Qtd</th><th>Nota min</th><th>Ativa</th><th>Criada em</th><th>Ações</th></tr>
      </thead>
      <tbody>
      <?php while($p = $res->fetch_assoc()): 
          $cat_logica = mapCategoriaProva($p['curso_categoria']);
      ?>
        <tr>
          <td><?= esc($p['id']) ?></td>
          <td><?= esc($p['titulo']) ?></td>
          <td><?= esc($p['curso_nome'] ?? '—') ?></td>
          <td><?= esc($cat_logica) ?></td>
          <td><?= esc($p['qtd_questoes']) ?></td>
          <td><?= esc($p['nota_minima']) ?></td>
          <td><?= $p['ativa'] ? 'Sim' : 'Não' ?></td>
          <td><?= esc($p['criada_em']) ?></td>
          <td>
            <a class="btn" href="criarprova.php?action=form&id=<?= $p['id'] ?>">Editar</a>
            <a class="btn" href="criarprova.php?action=questions&id=<?= $p['id'] ?>">Questões</a>
            <a class="btn del" href="criarprova.php?action=delete&id=<?= $p['id'] ?>" onclick="return confirm('Excluir prova e dados relacionados?')">Excluir</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>

    </body></html>
    <?php
    exit();
}

if ($action === 'form') {
    $cursos_q = $conn->query("SELECT Id_curso, Nome, Categoria FROM cursos ORDER BY Nome ASC");
    $prova = null;
    if (isset($_GET['id'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM provas WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $prova = $stmt->get_result()->fetch_assoc();
    }
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="utf-8"><title>NeoWise</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="criarprova.css">
  </head><body>
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
                    echo '<a href="perfil.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                } else {
                    echo '<a href="perfiladm.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                }

                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>
    <h1><?= $prova ? 'Editar' : 'Criar nova' ?> Prova</h1>
    <form method="post" action="criarprova.php?action=save">
      <input type="hidden" name="id" value="<?= $prova['id'] ?? 0 ?>">
      <label>Curso:</label><br>
      <select name="id_curso" required>
        <option value="">-- selecione --</option>
        <?php while($c = $cursos_q->fetch_assoc()): ?>
            <option value="<?= $c['Id_curso'] ?>" <?= ($prova && $prova['id_curso']==$c['Id_curso']) ? 'selected' : '' ?>>
                <?= esc($c['Nome']) ?> (<?= esc($c['Categoria']) ?>)
            </option>
        <?php endwhile; ?>
      </select><br><br>

      <label>Título:</label><br>
      <input type="text" name="titulo" value="<?= esc($prova['titulo'] ?? '') ?>" required><br><br>

      <label>Descrição:</label><br>
      <textarea name="descricao"><?= esc($prova['descricao'] ?? '') ?></textarea><br><br>

      <label>Quantidade de questões:</label><br>
      <input type="number" name="qtd_questoes" value="<?= esc($prova['qtd_questoes'] ?? 10) ?>"><br><br>

      <label>Nota mínima:</label><br>
      <input type="number" name="nota_minima" value="<?= esc($prova['nota_minima'] ?? 7) ?>"><br><br>

      <label><input type="checkbox" name="ativa" <?= (isset($prova['ativa']) ? ($prova['ativa'] ? 'checked' : '') : 'checked') ?>> Ativa</label>
      <br><br>

      <button type="submit"><?= $prova ? 'Salvar' : 'Criar' ?></button>
      <a href="criarprova.php?action=list">Cancelar</a>
    </form>
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
                <p>Email: contato@finwise.com.br</p>
                <p>Telefone: (11) 1234-5678</p>
                <p>Instagram: @finwise</p>
            </div>
        </div>
    </footer>
    </body></html>
    <?php
    exit();
}

if ($action === 'save' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $id_curso = intval($_POST['id_curso'] ?? 0);
    $titulo = trim($_POST['titulo'] ?? '');
    $descricao = trim($_POST['descricao'] ?? '');
    $qtd = intval($_POST['qtd_questoes'] ?? 10);
    $nota = intval($_POST['nota_minima'] ?? 7);
    $ativa = isset($_POST['ativa']) ? 1 : 0;

    $stmtC = $conn->prepare("SELECT Categoria FROM cursos WHERE Id_curso = ?");
    $stmtC->bind_param("i", $id_curso);
    $stmtC->execute();
    $rowC = $stmtC->get_result()->fetch_assoc();
    $categoria_logica = mapCategoriaProva($rowC['Categoria'] ?? '');

    if ($id > 0) {
        $sql = "UPDATE provas SET id_curso=?, titulo=?, descricao=?, qtd_questoes=?, nota_minima=?, ativa=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issiiii", $id_curso, $titulo, $descricao, $qtd, $nota, $ativa, $id);
        $stmt->execute();
        flash_set("Prova atualizada.");
    } else {
        $sql = "INSERT INTO provas (id_curso, titulo, descricao, qtd_questoes, nota_minima, ativa, criada_em) VALUES (?, ?, ?, ?, ?, ?, NOW())";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issiii", $id_curso, $titulo, $descricao, $qtd, $nota, $ativa);
        $stmt->execute();
        flash_set("Prova criada.");
    }

    redirect("criarprova.php?action=list");
}

if ($action === 'delete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);

    $stmt = $conn->prepare("DELETE r FROM respostas r WHERE r.id_prova = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM questoes WHERE id_prova = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM provas_aluno WHERE id_prova = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM provas WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    flash_set("Prova e dados relacionados excluídos.");
    redirect("criarprova.php?action=list");
}

if ($action === 'questions' && isset($_GET['id'])) {
    $id_prova = intval($_GET['id']);
    $stmt = $conn->prepare("SELECT p.*, c.Nome AS curso_nome, c.Categoria AS curso_categoria FROM provas p LEFT JOIN cursos c ON c.Id_curso = p.id_curso WHERE p.id = ?");
    $stmt->bind_param("i", $id_prova);
    $stmt->execute();
    $info = $stmt->get_result()->fetch_assoc();

    $stmt = $conn->prepare("SELECT * FROM questoes WHERE id_prova = ? ORDER BY id ASC");
    $stmt->bind_param("i", $id_prova);
    $stmt->execute();
    $qs = $stmt->get_result();

    $flash = flash_get();
    ?>

    <!DOCTYPE html>
    <html><head><meta charset="utf-8"><title>NeoWise</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="criarprova.css">
    </head><body>
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
                    echo '<a href="perfil.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                } else {
                    echo '<a href="perfiladm.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                }

                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>
    <h1>Questões da prova: <?= esc($info['titulo']) ?> (ID <?= esc($info['id']) ?>)</h1>
    <?php if ($flash): ?><div class="flash"><?= esc($flash) ?></div><?php endif; ?>
    <p>
      <a class="btn" href="criarprova.php?action=qform&new=1&id_prova=<?= $info['id'] ?>">+ Nova Questão</a>
      <a class="btn" href="criarprova.php?action=list">Voltar</a>
    </p>

    <table>
      <thead><tr><th>ID</th><th>Enunciado</th><th>Correta</th><th>Ações</th></tr></thead>
      <tbody>
      <?php while($q = $qs->fetch_assoc()): ?>
        <tr>
          <td><?= esc($q['id']) ?></td>
          <td><?= esc(mb_strimwidth($q['enunciado'],0,140,'...')) ?></td>
          <td><?= esc($q['correta']) ?></td>
          <td>
            <a class="btn" href="criarprova.php?action=qform&id=<?= $q['id'] ?>">Editar</a>
            <a class="btn" href="criarprova.php?action=qdelete&id=<?= $q['id'] ?>&id_prova=<?= $info['id'] ?>" onclick="return confirm('Excluir questão?')">Excluir</a>
          </td>
        </tr>
      <?php endwhile; ?>
      </tbody>
    </table>
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
                <p>Email: contato@finwise.com.br</p>
                <p>Telefone: (11) 1234-5678</p>
                <p>Instagram: @finwise</p>
            </div>
        </div>
    </footer>
    </body></html>
    <?php
    exit();
}

if ($action === 'qform') {
    $questao = null;
    $id_prova = intval($_GET['id_prova'] ?? ($_GET['id'] ? $_GET['id'] : 0));
    if (isset($_GET['id']) && !isset($_GET['new'])) {
        $id = intval($_GET['id']);
        $stmt = $conn->prepare("SELECT * FROM questoes WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $questao = $stmt->get_result()->fetch_assoc();
        $id_prova = $questao['id_prova'] ?? $id_prova;
    }
    $cursos_r = $conn->query("SELECT Id_curso, Nome FROM cursos ORDER BY Nome ASC");
    ?>
    <!DOCTYPE html>
    <html><head><meta charset="utf-8"><title>NeoWise</title>
    <link rel="stylesheet" href="header.css">
    <link rel="stylesheet" href="criarprova.css">
    </head><body>
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
                    echo '<a href="perfil.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                } else {
                    echo '<a href="perfiladm.php">' . '<li id="usuario_nome">Olá, ' . '<h3>' . htmlspecialchars($primeiro_nome) . '</h3>'. '</li></a>';
                }

                echo '<img src="' . $foto_perfil_url . '" alt="Foto de Perfil" class="foto-perfil">';
            }
            ?>
        </ul>
    </nav>
</header>
    <h1><?= $questao ? 'Editar' : 'Criar' ?> Questão</h1>
    <form method="post" action="criarprova.php?action=qsave">
      <input type="hidden" name="id" value="<?= esc($questao['id'] ?? 0) ?>">
      <input type="hidden" name="id_prova" value="<?= esc($id_prova) ?>">

      <label>Curso (referência):</label><br>
      <select name="id_curso" required>
        <option value="">-- selecione --</option>
        <?php while($c = $cursos_r->fetch_assoc()): ?>
            <option value="<?= $c['Id_curso'] ?>" <?= (isset($questao['id_curso']) && $questao['id_curso']==$c['Id_curso']) ? 'selected' : '' ?>>
                <?= esc($c['Nome']) ?>
            </option>
        <?php endwhile; ?>
      </select><br><br>

      <label>Enunciado:</label><br>
      <textarea name="enunciado" required><?= esc($questao['enunciado'] ?? '') ?></textarea><br><br>

      <label>A:</label><br>
      <input type="text" name="a" value="<?= esc($questao['alternativa_a'] ?? '') ?>" required><br><br>

      <label>B:</label><br>
      <input type="text" name="b" value="<?= esc($questao['alternativa_b'] ?? '') ?>" required><br><br>

      <label>C:</label><br>
      <input type="text" name="c" value="<?= esc($questao['alternativa_c'] ?? '') ?>" required><br><br>

      <label>D:</label><br>
      <input type="text" name="d" value="<?= esc($questao['alternativa_d'] ?? '') ?>" required><br><br>

      <label>Correta:</label><br>
      <select name="correta">
        <?php $sel = $questao['correta'] ?? 'A'; ?>
        <option value="A" <?= $sel==='A' ? 'selected' : '' ?>>A</option>
        <option value="B" <?= $sel==='B' ? 'selected' : '' ?>>B</option>
        <option value="C" <?= $sel==='C' ? 'selected' : '' ?>>C</option>
        <option value="D" <?= $sel==='D' ? 'selected' : '' ?>>D</option>
      </select>
      <br><br>

      <button type="submit"><?= $questao ? 'Salvar' : 'Criar' ?></button>
      <a href="criarprova.php?action=questions&id=<?= esc($id_prova) ?>">Cancelar</a>
    </form>
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
                <p>Email: contato@finwise.com.br</p>
                <p>Telefone: (11) 1234-5678</p>
                <p>Instagram: @finwise</p>
            </div>
        </div>
    </footer>
    </body></html>
    <?php
    exit();
}

if ($action === 'qsave' && $_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = intval($_POST['id'] ?? 0);
    $id_prova = intval($_POST['id_prova'] ?? 0);
    $id_curso = intval($_POST['id_curso'] ?? 0);
    $enunciado = trim($_POST['enunciado'] ?? '');
    $a = trim($_POST['a'] ?? '');
    $b = trim($_POST['b'] ?? '');
    $c = trim($_POST['c'] ?? '');
    $d = trim($_POST['d'] ?? '');
    $correta = strtoupper(substr(trim($_POST['correta'] ?? 'A'),0,1));
    if (!in_array($correta, ['A','B','C','D'])) $correta = 'A';

    if ($id > 0) {
        $stmt = $conn->prepare("UPDATE questoes SET id_curso=?, enunciado=?, alternativa_a=?, alternativa_b=?, alternativa_c=?, alternativa_d=?, correta=?, id_prova=? WHERE id=?");
        $stmt->bind_param("issssssii", $id_curso, $enunciado, $a, $b, $c, $d, $correta, $id_prova, $id);
        $stmt->execute();
        flash_set("Questão atualizada.");
    } else {
        $stmt = $conn->prepare("INSERT INTO questoes (id_curso, enunciado, alternativa_a, alternativa_b, alternativa_c, alternativa_d, correta, id_prova) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("issssssi", $id_curso, $enunciado, $a, $b, $c, $d, $correta, $id_prova);
        $stmt->execute();
        flash_set("Questão criada.");
    }

    redirect("criarprova.php?action=questions&id=" . $id_prova);
}

if ($action === 'qdelete' && isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $id_prova = intval($_GET['id_prova'] ?? 0);

    $stmt = $conn->prepare("DELETE FROM respostas WHERE id_questao = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $stmt = $conn->prepare("DELETE FROM questoes WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    flash_set("Questão excluída.");
    redirect("criarprova.php?action=questions&id=" . $id_prova);
}

http_response_code(404);
echo "Ação inválida";
exit();
