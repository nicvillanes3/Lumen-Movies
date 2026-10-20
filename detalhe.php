<?php
require 'config.php';

$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$item = null;

if ($id) {
    // 1. Tenta buscar no banco de dados local
    $stmt = $pdo->prepare("SELECT c.*, cat.nome AS categoria_nome FROM conteudos c JOIN categorias cat ON cat.id=c.categoria_id WHERE c.id=?");
    $stmt->execute([$id]);
    $item = $stmt->fetch();

    // 2. Se não encontrou no banco local, tenta buscar os detalhes na API do TMDB
    if (!$item) {
        $apiUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/tmdb_api.php?action=popular';
        $jsonApi = @file_get_contents($apiUrl);
        if ($jsonApi) {
            $dadosApi = json_decode($jsonApi, true);
            if (!empty($dadosApi['results'])) {
                foreach ($dadosApi['results'] as $filme) {
                    if ($filme['id'] == $id) {
                        $item = [
                            'id' => $filme['id'],
                            'titulo' => $filme['title'] ?? $filme['original_title'],
                            'descricao' => $filme['overview'] ?: 'Sem descrição disponível.',
                            'imagem_url' => !empty($filme['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $filme['poster_path'] : '',
                            'categoria_nome' => 'TMDB Popular',
                            'ano' => !empty($filme['release_date']) ? substr($filme['release_date'], 0, 4) : null,
                            'classificacao' => '12',
                            'duracao_minutos' => 120,
                            'destaque' => 1
                        ];
                        break;
                    }
                }
            }
        }
    }
}

if (!$item) { 
    http_response_code(404); 
    exit('Conteúdo não encontrado. <a href="index.php">Voltar</a>'); 
}

$favorito = false;
if (!empty($_SESSION['usuario_id'])) {
    $check = $pdo->prepare("SELECT id FROM favoritos WHERE usuario_id=? AND conteudo_id=?");
    $check->execute([$_SESSION['usuario_id'], $id]);
    $favorito = (bool)$check->fetch();
}
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($item['titulo']) ?> — LUMEN</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <a class="logo" href="index.php">LUM<span>E</span>N</a>
    <nav>
        <a href="index.php">Início</a>
        <a class="active" href="index.php#catalogo">Explorar</a>
        <?php if (!empty($_SESSION['usuario_id'])): ?>
            <a href="favoritos.php">Minha lista</a>
        <?php endif; ?>
    </nav>
    <div class="nav-actions">
        <?php if (!empty($_SESSION['usuario_id'])): ?>
            <a class="login-link" href="logout.php">Sair</a>
        <?php else: ?>
            <a class="login-link" href="login.php">Entrar</a>
        <?php endif; ?>
    </div>
</header>
<main>
<section class="detail-hero" style="--hero-image:url('<?= e($item['imagem_url'] ?: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1800&q=85') ?>')">
    <div class="detail-overlay"></div>
    <div class="detail-layout">
        <img class="detail-poster" src="<?= e($item['imagem_url'] ?: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=700&q=85') ?>" alt="Capa de <?= e($item['titulo']) ?>">
        <div class="detail-copy">
            <span class="eyebrow"><?= e($item['categoria_nome']) ?><?= $item['destaque'] ? ' · DESTAQUE LUMEN' : '' ?></span>
            <h1><?= e($item['titulo']) ?></h1>
            <div class="hero-meta">
                <?php if ($item['ano']): ?><span><?= e($item['ano']) ?></span><?php endif; ?>
                <?php if ($item['classificacao']): ?><span><?= e($item['classificacao']) ?></span><?php endif; ?>
                <?php if ($item['duracao_minutos']): ?><span><?= (int)$item['duracao_minutos'] ?> min</span><?php endif; ?>
            </div>
            <p><?= nl2br(e($item['descricao'])) ?></p>
            <div class="hero-buttons">
                <button class="button" type="button" id="playDemo">▶ &nbsp; Assistir trailer</button>
                <?php if (!empty($_SESSION['usuario_id'])): ?>
                    <form action="favorito_acao.php" method="post">
                        <input type="hidden" name="conteudo_id" value="<?= (int)$item['id'] ?>">
                        <input type="hidden" name="voltar" value="detalhe.php?id=<?= (int)$item['id'] ?>">
                        <button class="button button-ghost" type="submit"><?= $favorito ? '✓ Na minha lista' : '＋ Minha lista' ?></button>
                    </form>
                <?php else: ?>
                    <a class="button button-ghost" href="login.php">＋ Minha lista</a>
                <?php endif; ?>
            </div>
            <p class="demo-message" id="demoMessage" hidden>Prévia demonstrativa: este projeto apresenta o catálogo, não reproduz filmes.</p>
        </div>
    </div>
</section>
</main>
<footer class="footer">
    <a class="logo" href="index.php">LUM<span>E</span>N</a>
    <p>Onde a luz encontra as histórias.</p>
    <span>© <?= date('Y') ?> LUMEN · CINEMA · SÉRIES · EXPERIÊNCIAS</span>
</footer>
<script src="script.js"></script>
</body>
</html>