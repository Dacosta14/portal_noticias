<?php
session_start();
require 'db.php';
if ($_SESSION['user_tipo'] !== 'admin') {
    header("Location: login.php");
    exit;
}

$stmt = $pdo->query("SELECT * FROM noticias ORDER BY data_publicacao DESC");
$noticias = $stmt->fetchAll();

if (isset($_GET['aprovar'])) {
    $id_noticia = $_GET['aprovar'];
    $stmt = $pdo->prepare("UPDATE noticias SET status = 'aprovada' WHERE id = ?");
    $stmt->execute([$id_noticia]);
    header("Location: admin.php");
    exit;
}

if (isset($_GET['deletar'])) {
    $id_noticia = $_GET['deletar'];
    $stmt = $pdo->prepare("DELETE FROM noticias WHERE id = ?");
    $stmt->execute([$id_noticia]);
    header("Location: admin.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administração</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
            color: #333;
        }

        header {
            background-color: #333;
            color: #fff;
            padding: 15px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .container {
            padding: 20px;
        }

        /* Menu e Layout */
        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: flex-start;
        }

        nav ul li {
            margin: 0 10px;
        }

        nav ul li a {
            text-decoration: none;
            color: #fff;
            font-size: 18px;
        }

        nav {
            background-color: #444;
            padding: 10px 0;
        }

        /* Tabela */
        table {
            width: 100%;
            margin-top: 20px;
            border-collapse: collapse;
            background-color: #fff;
            border-radius: 8px;
            overflow: hidden;
        }

        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        th {
            background-color: #444;
            color: #fff;
        }

        tr:nth-child(even) {
            background-color: #f9f9f9;
        }

        tr:hover {
            background-color: #f1f1f1;
        }

        .actions a {
            color: #4CAF50;
            margin-right: 10px;
            text-decoration: none;
        }

        .actions a:hover {
            color: #45a049;
        }

        /* Botão Voltar */
        #voltar {
            background-color: #444;
            color: #fff;
            border: none;
            padding: 10px 20px;
            margin-top: 20px;
            cursor: pointer;
            border-radius: 5px;
        }

        #voltar:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header>
        <div class="container">
            <h1>Administração</h1>
        </div>
    </header>



    <main class="container">
        <button id="voltar" onclick="window.location.href='index.php';">Voltar para o Início</button>

        <table>
            <tr>
                <th>Título</th>
                <th>Status</th>
                <th>Ações</th>
            </tr>
            <?php foreach ($noticias as $noticia): ?>
            <tr>
                <td><?= $noticia['titulo'] ?></td>
                <td><?= ucfirst($noticia['status']) ?></td>
                <td class="actions">
                    <?php if ($noticia['status'] === 'pendente'): ?>
                        <a href="?aprovar=<?= $noticia['id'] ?>">Aprovar</a>
                    <?php endif; ?>
                    <a href="?deletar=<?= $noticia['id'] ?>">Deletar</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </table>
    </main>

    <footer>
        <div class="container">
            <p>&copy; 2024 Mais Esporte. Todos os direitos reservados.</p>
        </div>
    </footer>

</body>
</html>
