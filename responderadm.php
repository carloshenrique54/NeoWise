<?php
require('conexao.php');
require ('foto-perfil.php');
session_start();

if ($_SESSION['usuario']['acesso'] != 1) {
    header('Location: index.php'); 
    exit();
}

if (isset($_GET['id'])) 
    $id_contato = $_GET['id'];

    
    $sql = "SELECT * FROM mensagem WHERE id_mensagem = ?";
    if ($stmt = $conn->prepare($sql)) {
        $stmt->bind_param("i", $id_contato);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
        } else {
            echo "Mensagem não encontrada.";
            exit();
        }
    } else {
        echo "Erro na consulta ao banco de dados.";
        exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $resposta = filter_input(INPUT_POST, 'resposta', FILTER_SANITIZE_STRING);

    if (empty($resposta)) {
        echo "Por favor, escreva uma resposta.";
        exit();
    }

    $sql_update = "UPDATE mensagem SET Respondido = 'SIM', respostas = ? WHERE id_contato = ?";
    if ($stmt_update = $conn->prepare($sql_update)) {
        $stmt_update->bind_param("si", $resposta, $id_contato);
        if ($stmt_update->execute()) {
            echo "Resposta enviada e salva com sucesso!";
        } else {
            echo "Erro ao salvar a resposta.";
        }
    } else {
        echo "Erro na atualização da mensagem.";
    }


    echo "<script>
        emailjs.init('yexqvxDUHrrXPU6Wf'); 
        emailjs.send('service_dn2sl3i', 'template_f9ffhs9', {
            email: '" . $row['email'] . "',  
            assunto: '" . $row['Assunto'] . "',  
            mensagem: '" . $row['Mensagem'] . "',  
            resposta: '" . $resposta . "' 
        }).then(function(response) {
            alert('E-mail enviado com sucesso!');
        }, function(error) {
            alert('Erro ao enviar o e-mail. Tente novamente.');
        });
    </script>";

    echo "<script>window.location.href = 'index.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responder Mensagem</title>
    <link rel="icon" href="Mídias/icone.ico">
    <script type="text/javascript">
        (function(){
            emailjs.init("yexqvxDUHrrXPU6Wf"); // Inicializa o EmailJS com sua chave
        })();
    </script>
</head>
<body>
    <h2>Responder à Mensagem</h2>

    <form id="resposta-form" method="POST">
        <input type="hidden" id="id_contato" name="id_contato" value="<?php echo $row['id_contato']; ?>">
        
        <div>
            <strong>Assunto:</strong> <?php echo htmlspecialchars($row['Assunto']); ?>
        </div>
        
        <div>
            <strong>Mensagem Original:</strong><br>
            <?php echo nl2br(htmlspecialchars($row['Mensagem'])); ?>
        </div>

        <div>
            <label for="resposta">Sua Resposta:</label><br>
            <textarea id="resposta" name="resposta" rows="5" cols="40" required></textarea>
        </div>
        
        <div>
            <button type="submit">Enviar Resposta</button>
        </div>
    </form>

    <div id="toast"></div>
</body>
</html>
