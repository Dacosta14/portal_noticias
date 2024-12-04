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
  <link rel="stylesheet" href="./index.css">
  <link rel="stylesheet" href="./src/css/admin.css">
</head>

<body>
  <!--------------------- HEADER---------------------->
  <header class="header">
    <h1 class="header__title">Administração</h1>
  </header>
  <!---------------------- MAIN ---------------------->
  <main class="main">
    <button 
      class="main__btn" 
      id="voltar" 
      onclick="window.location.href='index.php';"
    >
      Voltar para o Início
    </button>
    <table class="main__table">
      <tr class="table__row">
        <th class="table__text table__header-text">Título</th>
        <th class="table__text table__header-text">Status</th>
        <th class="table__text table__header-text">Ações</th>
      </tr>
      <?php foreach ($noticias as $noticia): ?>
        <tr class="table__row">
          <td class="table__text">
            <?= $noticia['titulo'] ?>
          </td>
          <td class="table__text">
            <?= ucfirst($noticia['status']) ?>
          </td>
          <td class="table__text">
            <?php if ($noticia['status'] === 'pendente'): ?>
            <a class="text__link" href="?aprovar=<?= $noticia['id'] ?>">Aprovar</a>
            <?php endif; ?>
            <a class="text__link" href="?deletar=<?= $noticia['id'] ?>">Deletar</a>
          </td>
        </tr>
      <?php endforeach; ?>
    </table>
  </main>
  <!---------------------- FOOTER ---------------------->
  <footer class="footer">
    <p class="footer__text">
      &copy; 2024 Mais Esporte. Todos os direitos reservados.
    </p>
  </footer>
</body>
</html>