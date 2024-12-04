    <?php
session_start();
require 'db.php';
if (isset($_COOKIE['user_id']) && isset($_COOKIE['user_tipo'])) {
    $_SESSION['user_id'] = $_COOKIE['user_id'];
    $_SESSION['user_tipo'] = $_COOKIE['user_tipo'];
}

// Verifica se o usuário está logado
$usuario_logado = isset($_SESSION['user_id']) && isset($_SESSION['user_tipo']);

$stmt = $pdo->query("SELECT * FROM noticias WHERE status = 'aprovada' ORDER BY data_publicacao DESC");
$noticias = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mais Esporte</title>
    <link rel="stylesheet" href="index.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background-color: #181818;
            color: #e0e0e0;
            margin: 0;
            padding: 0;
        }

        header, footer {
            background-color: #232323;
            color: #e0e0e0;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
        }

        header h1 {
            font-size: 24px;
            margin: 0;
        }

        .logo img {
            width: 100px;
            height: auto;
        }

        nav {
            background-color: #2a2a2a;
        }

        nav ul {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            justify-content: flex-start;
        }

        nav ul li {
            margin: 0 15px;
        }
    
        nav ul li a {
            color: #81c784;
            text-decoration: none;
            font-size: 18px;
            font-weight: 600;
        }

        nav ul li a:hover {
            color: #aed581;
        }

        .menu-icon {
            display: none;
            font-size: 30px;
            cursor: pointer;
        }

        /* Estilos para os botões */
        button {
            background-color: #616161;
            color: #fff;
            padding: 12px 20px;
            border: none;
            cursor: pointer;
            border-radius: 8px;
            font-weight: bold;
        }

        button:hover {
            background-color: #757575;
        }

        /* Ajustes de imagem */
        img {
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
            width: 100%;
            max-width: 500px;
            margin-top: 10px;
        }

        /* Layout de notícias */
        .destaques {
            padding: 20px;
        }

        .destaques h2 {
            font-size: 28px;
            margin-bottom: 20px;
        }

        .destaques article {
            background-color: #333;
            padding: 20px;
            border-radius: 10px;
            margin-bottom: 20px;
        }

        .destaques h3 {
            font-size: 22px;
            margin-bottom: 10px;
        }

        .destaques p {
            font-size: 16px;
            line-height: 1.6;
        }

        /* Menu responsivo */
        @media (max-width: 768px) {
            nav ul {
                flex-direction: column;
                width: 100%;
                background-color: #333;
                position: absolute;
                top: 60px;
                left: 0;
                padding: 10px 0;
                display: none;
            }

            nav ul.show {
                display: block;
            }

            nav ul li {
                text-align: center;
                margin: 10px 0;
            }

            nav ul li a {
                color: #fff;
            }

            .menu-icon {
                display: block;
            }
        }
        footer {
    background-color: #232323; 
    color: #e0e0e0;
    padding: 15px;
    display: flex; 
    justify-content: center; 
    align-items: center; 
    border-top: 1px solid #444; 
    margin-top: 20px;
}

    </style>
</head>
<body>
<header>
    <div class="menu-icon" onclick="toggleMenu()">☰</div>
    <h1>Mais Esporte</h1>
    <div class="logo">
        <a href="index.php">
            <img src="logoportal.jpg" alt="Logo Mais Esporte" />
        </a>
    </div>
</header>

<nav>
    <ul id="menu">
        <li><a href="index.php" class="<?= $usuario_logado ? 'dark-mode' : '' ?>">Início</a></li>
        <?php if ($usuario_logado): ?>
            <li>
                <?php if ($_SESSION['user_tipo'] === 'admin'): ?>
                    <a href="admin.php" class="<?= $usuario_logado ? 'dark-mode' : '' ?>">Painel de Administração</a>
                <?php elseif ($_SESSION['user_tipo'] === 'escritor'): ?>
                    <a href="escritor.php" class="<?= $usuario_logado ? 'dark-mode' : '' ?>">Painel do Escritor</a>
                <?php endif; ?>
            </li>
            <li><a href="logout.php" class="<?= $usuario_logado ? 'dark-mode' : '' ?>">Sair</a></li>
        <?php else: ?>
            <li><a href="login.php" class="<?= $usuario_logado ? 'dark-mode' : '' ?>">Login</a></li>
            <li><a href="cadastro.php" class="<?= $usuario_logado ? 'dark-mode' : '' ?>">Cadastrar</a></li>
        <?php endif; ?>
    </ul>
</nav>

<main>
    <section class="destaques">
        <h2>Destaques da Semana</h2>
        <article>
            <?php foreach ($noticias as $noticia): ?>
                
                    <h3><?= $noticia['titulo'] ?></h3>
                    <p><?= $noticia['conteudo'] ?></p>
                    <?php if (!empty($noticia['imagem'])): ?>
                        <img src="<?= $noticia['imagem'] ?>" alt="Imagem da notícia" />
                    <?php endif; ?>
                    <small>Publicado em: <?= $noticia['data_publicacao'] ?></small>
               
            <?php endforeach; ?>
        </article>
    </section>
</main>

<script>
    function toggleMenu() {
        var menu = document.getElementById('menu');
        menu.classList.toggle('show');
    }
</script>
<footer>
    <div class="container">
        <p>&copy; 2024 Mais Esporte. Todos os direitos reservados.</p>
    </div>
</footer>
</body>
</html>
