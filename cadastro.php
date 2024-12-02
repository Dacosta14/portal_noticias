<?php
session_start();
require 'db.php';

// Verifica se o usuário já está logado
if (isset($_SESSION['user_id'])) {
    header("Location: index.php"); // Redireciona para a página principal se o usuário já estiver logado
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pega os dados do formulário
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];

    // Verifica se o email já está cadastrado
    $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = ?");
    $stmt->execute([$email]);
    $usuarioExistente = $stmt->fetch();

    if ($usuarioExistente) {
        $erro = "Já existe um usuário com esse email.";
    } else {
        // Insere o novo usuário no banco de dados
        $stmt = $pdo->prepare("INSERT INTO usuarios (nome, email, senha, tipo) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nome, $email, $senha, $tipo]);

        $msg = "Cadastro realizado com sucesso! Agora você pode fazer login.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Portal Mais Esporte</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #181818;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }

        header {
            background-color: #232323;
            color: #e0e0e0;
            padding: 15px 0;
            text-align: center;
        }

        header h1 {
            margin: 0;
            font-size: 2rem;
        }

        .menu-icon span {
            font-size: 24px;
            display: block;
        }

        .logo img {
            max-width: 100px;
        }

        nav {
            background-color: #2a2a2a;
            padding: 10px 0;
        }

        nav ul {
            list-style: none;
            display: flex;
            justify-content: center;
            padding: 0;
        }

        nav ul li {
            margin: 0 15px;
        }

        nav ul li a {
            color: #81c784;
            text-decoration: none;
            font-size: 1.1rem;
        }

        nav ul li a:hover {
            color: #aed581;
        }

        .container {
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            background-color: #333;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h2 {
            text-align: center;
            font-size: 1.8rem;
            margin-bottom: 20px;
        }

        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin-bottom: 8px;
            font-weight: bold;
        }

        input, select {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #444;
            color: #fff;
        }

        input:focus, select:focus {
            border-color: #81c784;
            outline: none;
        }

        button {
            padding: 10px 20px;
            border: none;
            background-color: #81c784;
            color: #fff;
            cursor: pointer;
            border-radius: 5px;
        }

        button:hover {
            background-color: #66bb6a;
        }

        p {
            text-align: center;
            margin-top: 15px;
        }

        .error, .success {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
        }

        .error {
            color: red;
        }

        .success {
            color: green;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #232323;
        }

    </style>
</head>
<body>

<header>
    <div class="menu-icon">
        <span></span>
    </div>
    <h1>Mais Esporte</h1>
    <div class="logo">
        <a href="index.php">
            <img src="logoportal.jpg" alt="Logo Mais Esporte" class="logo-img" />
        </a>
    </div>
</header>

<nav>
    <ul>
        <li><a href="index.php" class="dark-mode">Início</a></li>

    </ul>
</nav>

<main>
    <div class="container">
        <h2>Crie sua conta</h2>

        <?php if (isset($erro)): ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>
        <?php if (isset($msg)): ?>
            <p class="success"><?= $msg ?></p>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="nome">Nome:</label>
                <input type="text" name="nome" id="nome" required>
            </div>
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <div>
                <label for="tipo">Tipo de Usuário:</label>
                <select name="tipo" id="tipo" required>
                    <option value="escritor">Escritor</option>
            
                </select>
            </div>
            <button type="submit">Cadastrar</button>
        </form>

        <p>Já tem uma conta? <a href="login.php" class="dark-mode">Faça login aqui</a></p>
    </div>
</main>

<footer>
    <p>&copy; 2024 Mais Esporte. Todos os direitos reservados.</p>
</footer>

</body>
</html>
