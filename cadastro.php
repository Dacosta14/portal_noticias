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
    <link rel="stylesheet" href="./src/css/cadastro.css">
    <link rel="stylesheet" href="index.css">
</head>

<body>
  <!------------------------ HEADER ------------------------>
  <header class="header">
    <h1 class="header__title">Mais Esporte</h1>
      <a class="header__logo-box" href="index.php">
        <img 
          class="logo-box__img" 
          src="logoportal.jpg" 
          alt="Logo Mais Esporte" 
        />
      </a>
  </header>
  <!------------------------ MAIN ------------------------>
  <main class="main">
    <button 
        class="main__btn" 
        onclick="window.location.href='index.php'"
      >
        Voltar para o Início
    </button>
    <div class="main__card">
      <h2 class="card__title">Crie sua conta</h2>
      <?php if (isset($erro)): ?>
        <p class="card__msg card__msg--error"><?= $erro ?></p>
      <?php endif; ?>
      <?php if (isset($msg)): ?>
        <p class="card__msg card__msg--success"><?= $msg ?></p>
      <?php endif; ?>
      <form class="card__form"method="POST">
        <div class="form__item">
          <label class="form__label" for="nome">Nome:</label>
          <input 
            class="form__input" 
            type="text" 
            name="nome" 
            id="nome" 
            required
          >
        </div>
        <div class="form__item">
          <label class="form__label" for="email">Email:</label>
          <input 
            class="form__input" 
            type="email" 
            name="email" 
            id="email" 
            required
          >
        </div>
        <div class="form__item">
          <label class="form__label" for="senha">Senha:</label>
          <input 
            class="form__input" 
            type="password" 
            name="senha" 
            id="senha" 
            required
          >
        </div>
        <div class="form__item">
          <label class="form__label" for="tipo">Tipo de Usuário:</label>
          <select 
            class="form__input" 
            name="tipo" 
            id="tipo" 
            required
          >
            <option value="escritor">Escritor</option>
          </select>
        </div>
        <button class="form__button" type="submit">Cadastrar</button>
      </form>
      <p class="card__text">
        Já tem uma conta? <a class="card__link" href="login.php">Faça login aqui</a><!-- dark-mode  -->
      </p>
    </div>
  </main>
  <!------------------------ FOOTER ------------------------>
  <footer class="footer">
    <p class="footer__text">
        &copy; 2024 Mais Esporte. Todos os direitos reservados.
    </p>
  </footer>
</body>
</html>
