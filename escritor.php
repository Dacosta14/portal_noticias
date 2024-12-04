<?php
session_start();
require 'db.php';

// Verifica se o usuário está autenticado como escritor
if ($_SESSION['user_tipo'] !== 'escritor') {
    header("Location: login.php");
    exit;
}

$msg = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titulo = $_POST['titulo'];
    $conteudo = $_POST['conteudo'];
    $autor_id = $_SESSION['user_id'];

    if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] == 0) {
        $imagem_nome = $_FILES['imagem']['name'];
        $imagem_tmp = $_FILES['imagem']['tmp_name'];

        // Define o caminho onde a imagem será salva
        $pasta_imagens = 'uploads/';
        if (!is_dir($pasta_imagens)) {
            mkdir($pasta_imagens, 0777, true); // Cria a pasta com permissões adequadas
        }

        $imagem_destino = $pasta_imagens . basename($imagem_nome);

        if (move_uploaded_file($imagem_tmp, $imagem_destino)) {
            $stmt = $pdo->prepare("INSERT INTO noticias (titulo, conteudo, imagem, status, autor_id) VALUES (?, ?, ?, 'pendente', ?)");
            $stmt->execute([$titulo, $conteudo, $imagem_destino, $autor_id]);
            $msg = "Notícia enviada para aprovação com imagem!";
        } else {
            $msg = "Erro no upload da imagem!";
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO noticias (titulo, conteudo, status, autor_id) VALUES (?, ?, 'pendente', ?)");
        $stmt->execute([$titulo, $conteudo, $autor_id]);
        $msg = "Notícia enviada para aprovação sem imagem!";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Criar Notícia - Portal Mais Esporte</title>
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            background-color: #181818;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
        }

        header, footer {
            background-color: #232323;
            color: #e0e0e0;
            padding: 20px;
            text-align: center;
        }

        .container {
            max-width: 400px;
            margin: 2rem auto;
            padding: 20px;
            background-color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            text-align: center;
            font-size: 2rem;
            margin-bottom: 20px;
        }
        form {
            display: flex;
            flex-direction: column;
            width: 100%;
        }
        input[type="text"], textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #444;
            color: #fff;
        }

        input[type="text"]:focus, textarea:focus {
            border-color: #81c784;
            outline: none;
        }

        /* Estilização do input de arquivo */
        input[type="file"] {
            display: none; /* Oculta o input original */
        }

        .upload-label {
            display: inline-block;
            padding: 10px 20px;
            background-color: #81c784;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            text-align: center;
            transition: background-color 0.3s ease;
        }

        .upload-label:hover {
            background-color: #66bb6a;
        }

        .upload-label:active {
            background-color: #4caf50;
        }

        /* Para exibir o nome do arquivo selecionado */
        .file-name {
            display: block;
            margin-top: 10px;
            font-size: 0.9rem;
            color: #ccc;
            text-align: center;
        }

        button {
            width: 30%;
            padding: 12px 20px;
            border: none;
            background-color: #81c784;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
            margin-right: 10px;
        }

        button:hover {
            background-color: #66bb6a;
        }

        .msg {
            text-align: center;
            margin-top: 20px;
            font-size: 1.2rem;
        }

        .msg.success {
            color: green;
        }

        .msg.error {
            color: red;
        }
        footer {
            display: flex;
            align-items: center;
            justify-content: center;
            position: absolute;
            bottom: 1rem;
            right: 0;
            left: 0;
            background-color: #232323;
            color: #e0e0e0;
            padding: 10px;
            background-color: #232323;
   
        }
        footer p {
            margin: 0;
        }
      

        .btn-container {
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<header>
    <h1>Criar Notícia</h1>
</header>

<main>
    <div class="container">
        <?php if (!empty($msg)): ?>
            <p class="msg <?= strpos($msg, 'erro') !== false ? 'error' : 'success'; ?>"><?= $msg ?></p>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="titulo" placeholder="Título da Notícia" required>
            <textarea name="conteudo" placeholder="Conteúdo da Notícia" required></textarea>

            <!-- Campo de upload estilizado -->
            <label for="file-upload" class="upload-label">Escolher Imagem</label>
            <input type="file" id="file-upload" name="imagem">
            <span id="file-name" class="file-name">Nenhum arquivo selecionado</span>

            <div class="btn-container">
                <a href="index.php">
                    <button type="button">Voltar ao Início</button>
                </a>
                <button type="submit">Enviar</button>
            </div>
        </form>
    </div>
</main>

<footer>
    <p>&copy; 2024 Mais Esporte. Todos os direitos reservados.</p>
</footer>

<script>
    // Exibe o nome do arquivo selecionado
    const fileInput = document.getElementById('file-upload');
    const fileNameDisplay = document.getElementById('file-name');

    fileInput.addEventListener('change', function () {
        const fileName = fileInput.files.length ? fileInput.files[0].name : 'Nenhum arquivo selecionado';
        fileNameDisplay.textContent = fileName;
    });
</script>

</body>
</html>
