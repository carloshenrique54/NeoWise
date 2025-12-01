<?php
require('conexao.php');
session_start(); 

if (!isset($_SESSION['curtidas'])) {
    $_SESSION['curtidas'] = [];
}

$cpf_usuario_logado = $_SESSION['usuario']['cpf'] ?? null;

if (isset($_GET['like_id']) && is_numeric($_GET['like_id'])) {
    $id_mensagem = $_GET['like_id'];
    $key = array_search($id_mensagem, $_SESSION['curtidas']);
    
    if ($key !== false) {
        $sql_update = "UPDATE forum SET likes = likes - 1 WHERE idmensagem = ?";
        unset($_SESSION['curtidas'][$key]); 
    } else {
        $sql_update = "UPDATE forum SET likes = likes + 1 WHERE idmensagem = ?";
        $_SESSION['curtidas'][] = $id_mensagem;
    }

    $stmt = $conn->prepare($sql_update);
    if ($stmt) {
        $stmt->bind_param("i", $id_mensagem);
        $stmt->execute();
        $stmt->close();
    }
    
    header("Location: forum.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comentario_texto']) && isset($_POST['idmensagem_comentario'])) {
    
    $id_mensagem_comentario = $_POST['idmensagem_comentario'];
    $comentario_texto = $_POST['comentario_texto'];
    
    if ($cpf_usuario_logado && !empty($comentario_texto)) {
        
        $sql_insert_comentario = "
            INSERT INTO forum_comentarios (idmensagem, cpfusuario, comentario) VALUES (?, ?, ?)
        ";
        
        $stmt_i = $conn->prepare($sql_insert_comentario);

        if ($stmt_i) {
            $stmt_i->bind_param("iss", $id_mensagem_comentario, $cpf_usuario_logado, $comentario_texto);
            $stmt_i->execute();
            $stmt_i->close();
        }
        
        header("Location: forum.php#msg" . $id_mensagem_comentario);
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise</title>
    <link rel="stylesheet" href="forum.css">
    <link rel="icon" href="Mídias/icone.ico">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css">
    <link rel="stylesheet" href="header.css">
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
                $foto_perfil_url = $_SESSION['usuario']['foto'] ?? "Mídias/perfil.png";

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
<main>
    <div id="forum-head">
        <div id="forum-title">
            <h1>Fórum</h1>
            <button id="btnAbrirModal">Faça seu post</button>
        </div>
        <div class="forum-filtro">
            <input type="text" id="pesquisa" placeholder="Pesquisar por tópico...">
            <select id="filtro-topico">
                <option value="">Todos os tópicos</option>
                <option value="Programação">Programação</option>
                <option value="Design">Design</option>
                <option value="Dúvidas">Dúvidas</option>
            </select>
            <button id="btn-filtrar">Filtrar</button>
        </div>
    </div>
    
    <div id="modalMensagem" class="modal">
  <div class="modal-conteudo">
    <span class="fechar">&times;</span>
    <h2>Postar Nova Mensagem</h2>

    <form method="POST" action="forum_add.php">
      <label for="mensagem">Insira a mensagem</label><br>
      <input type="text" name="mensagem" id="mensagem" required><br><br>

      <label for="topico">Selecione o Curso (Tópico)</label><br>
      <select name="topico" id="topico" required>
        <option value="">-- Escolha o Curso --</option>
        <?php
        $sql_cursos = "SELECT Id_curso, Nome FROM cursos ORDER BY Nome"; 
        $resultado = $conn->query($sql_cursos);

        if ($resultado && $resultado->num_rows > 0) {
            while($row = $resultado->fetch_assoc()){
                echo '<option value="' . htmlspecialchars($row['Nome']) . '">' . htmlspecialchars($row['Nome']) . '</option>';
            }
        } else {
            echo '<option value="" disabled>Erro ou Nenhum curso encontrado.</option>';
        }

        if (isset($resultado) && $resultado instanceof mysqli_result) {
            $resultado->free();
        }
        ?>
      </select><br><br>

      <input type="submit" value="Postar">
    </form>
  </div>
</div>

    <?php
    $sql_select = "
        SELECT 
            f.idmensagem, 
            f.mensagem, 
            f.hr, 
            f.topico,
            f.likes,  
            u.nome AS nome_usuario,
            u.foto AS foto_usuario 
        FROM 
            forum AS f  
        LEFT JOIN 
            alunos AS u ON f.cpfmensagem = u.cpf  
        ORDER BY 
            f.hr DESC
    ";
    
    $resultado = $conn->query($sql_select);
    
    if ($resultado && $resultado->num_rows > 0) {
        
        while ($row = $resultado->fetch_assoc()) {
            
            $nome_exibicao = $row['nome_usuario'] ? htmlspecialchars($row['nome_usuario']) : 'Usuário Desconhecido';
            $id_mensagem = htmlspecialchars($row['idmensagem']);
            $foto_caminho = $row['foto_usuario'] ? htmlspecialchars($row['foto_usuario']) : 'Mídias/perfil.png'; 
            
            $curtiu_na_sessao = in_array($id_mensagem, $_SESSION['curtidas']);
            $btn_class = $curtiu_na_sessao ? 'like-btn like-btn-curtido' : 'like-btn';
            $icone_class = $curtiu_na_sessao ? 'fa-solid' : 'fa-regular';
            
            echo '<div class="mensagem-container" id="msg' . $id_mensagem . '">';
            
            echo '<div class="conteudo-mensagem">';
            echo '<div class="area-deletar">';
            echo '<div class="header-mensagem">';

            echo '<img src="' . $foto_caminho . '" alt="' . $nome_exibicao . '" class="foto-usuario">';

            echo '<div class="mensagem-info">';
            echo 'Tópico: <strong>' . htmlspecialchars($row['topico']) . '</strong><br>';
            echo 'Enviada por: <strong>' . $nome_exibicao . '</strong> em ' . htmlspecialchars($row['hr']);
            echo '</div>';
            echo '</div>';
            if ($_SESSION['usuario']['acesso'] > 0){
                echo '<button class="btn-deletar" type="submit">Deletar</button>';
            }
            echo '</div>';
            
            echo '<p class="mensagem-texto">' . nl2br(htmlspecialchars($row['mensagem'])) . '</p>';
            
            echo '<div>';
            echo '<a href="?like_id=' . $id_mensagem . '" class="' . $btn_class . '"><i class="' . $icone_class . ' fa-thumbs-up"></i></a>';
            echo htmlspecialchars($row['likes']) .' '.'Curtidas';
            echo '</a>';
            echo '</div>';

            echo '<div class="comentarios-area">';
            echo '<strong>Comentários:</strong>';

            $sql_comentarios = "
                SELECT 
                    c.comentario,
                    c.hr AS data_comentario,
                    a.nome AS nome_comentador
                FROM 
                    forum_comentarios AS c
                LEFT JOIN
                    alunos AS a ON c.cpfusuario = a.cpf
                WHERE
                    c.idmensagem = ?
                ORDER BY c.hr ASC
            ";

            $stmt_c = $conn->prepare($sql_comentarios);
            $stmt_c->bind_param("i", $id_mensagem);
            $stmt_c->execute();
            $resultado_c = $stmt_c->get_result();

            if ($resultado_c->num_rows > 0) {
                while ($comentario = $resultado_c->fetch_assoc()) {
                    $nome_comentador = $comentario['nome_comentador'] ? htmlspecialchars($comentario['nome_comentador']) : 'Aluno Desconhecido';

                    echo '<div class="comentario-item">';
                    echo '<span class="comentario-meta">' . '<strong>' . $nome_comentador . '</strong>' . ' em ' . htmlspecialchars($comentario['data_comentario']) . ':</span>';
                    echo '<p class="comentario-texto">' . nl2br(htmlspecialchars($comentario['comentario'])) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p class="comentario-texto">Nenhum comentário.</p>';
            }

            $stmt_c->close();

            if ($cpf_usuario_logado) {
                echo '<form method="POST" action="forum.php" class="comentario-form">';
                echo '<input type="hidden" name="idmensagem_comentario" value="' . $id_mensagem . '">';
                echo '<input class="fazer-comentario" type="text" name="comentario_texto" placeholder="Deixe seu comentário..." required>';
                echo '<button class="btn-comentar" type="submit">Comentar</button>';
                echo '</form>';
            } else {
                 echo '<p class="comentario-texto">Faça login para comentar.</p>';
            }
            
            echo '</div>'; 
            
            echo '</div>';
            
            echo '</div>'; 
        }
        
    } else {
        echo '<p class="sem-mensagens">Nenhuma mensagem foi postada ainda, seja o primeiro!</p>';
    }

    if (isset($resultado)) {
        $resultado->free();
    }
    ?>
    <script src="forum.js"></script>
    <script src="tema.js"></script>
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
</body>
</html>