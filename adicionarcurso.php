<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NeoWise</title>
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
    <div id="add-curso">
    <h2>Adicionar Curso</h2>
    <form method="POST" action="add.php">
        <input type="hidden" name="acao" value="adicionar">
        <input type="text" name="nome_curso" placeholder="Nome do curso" required>
        <input type="text" name="categoria" placeholder="Categoria" required>
        <textarea name="descriçao" placeholder="Descrição" required></textarea>
        <input type="text" name="conteudo" placeholder="Conteúdo" required>
        <input type="text" name="atividades" placeholder="Atividades" required>
        <input type="text" name="beneficios" placeholder="Benefícios" required>
        <input type="number" name="preco" placeholder="Preço" required>
        <input type="number" name="preco_parce" placeholder="Preço Parcelado" required>
        <input type="text" name="imagem" placeholder="Coloque a imagem" require>
        <input type="submit" value="Adicionar Curso">
</form>
</body>
</html>