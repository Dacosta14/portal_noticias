<?php
session_start();
require 'db.php';

if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_tipo'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['user_tipo'] = $_COOKIE['user_tipo'];
    
    header("Location: " . ($_SESSION['user_tipo'] === 'admin' ? 'index.php' : 'index.php'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $senha = $_POST['senha'] ?? null;

    if ($email && $senha) {  
        $stmt = $pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch();

        if ($user && $senha == $user['senha']) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_tipo'] = $user['tipo'];

            setcookie('user_id', $user['id'], time() + (30 * 24 * 60 * 60), "/"); 
            setcookie('user_tipo', $user['tipo'], time() + (30 * 24 * 60 * 60), "/"); 

            header("Location: " . ($user['tipo'] === 'admin' ? 'index.php' : 'index.php'));
            exit;
        } else {

            $erro = "Credenciais inválidas!";
        }
    } else {

        $erro = "Por favor, preencha todos os campos.";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Portal Mais Esporte</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body.dark-mode {
            background-color: #181818;
            color: #e0e0e0;
            font-family: Arial, sans-serif;
        }

        header.dark-mode, footer.dark-mode {
            background-color: #232323;
            color: #e0e0e0;
        }

        nav.dark-mode {
            background-color: #2a2a2a;
        }

        a.dark-mode {
            color: #81c784;
            text-decoration: none;
        }

        a.dark-mode:hover {
            color: #aed581;
        }

        button.dark-mode {
            background-color: #616161;
            color: #fff;
        }

        button.dark-mode:hover {
            background-color: #757575;
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

        input {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #444;
            border-radius: 5px;
            background-color: #444;
            color: #fff;
        }

        input:focus {
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

        .error {
            text-align: center;
            margin-bottom: 20px;
            color: red;
            font-weight: bold;
        }

        footer {
            text-align: center;
            padding: 10px;
            background-color: #232323;
        }
        h1 {
        color: white; 
    }
    header {
    background-color: #232323; 
    color: #000;
    padding: 20px; 
    text-align: center;
}


    </style>
</head>
<body class="dark-mode">

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


<main>
    <div class="container">
        <h2>Login</h2>

 
        <?php if (isset($erro)): ?>
            <p class="error"><?= $erro ?></p>
        <?php endif; ?>

        <form method="POST">
            <div>
                <label for="email">Email:</label>
                <input type="email" name="email" id="email" required>
            </div>
            <div>
                <label for="senha">Senha:</label>
                <input type="password" name="senha" id="senha" required>
            </div>
            <button type="submit">Entrar</button>
        </form>
        <p>Não tem uma conta? <a href="cadastro.php" class="dark-mode">Crie uma aqui</a></p>
  
        <button onclick="window.location.href='index.php';" class="dark-mode">Voltar para o Início</button>
    </div>
</main>

<footer class="dark-mode">
    <p>&copy; 2024 Mais Esporte. Todos os direitos reservados.</p>
</footer>

</body>
</html>
