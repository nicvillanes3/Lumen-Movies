<?php
require 'config.php';

// Se o cookie 'viu_abertura' não existir, redireciona para a abertura
if (!isset($_COOKIE['viu_abertura'])) {
    header('Location: abertura.php');
    exit;
}

// Tenta buscar filmes em alta da API do TMDB
$tmdbConteudos = [];
$apiUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/tmdb_api.php?action=popular';

$jsonApi = @file_get_contents($apiUrl);
if ($jsonApi) {
    $dadosApi = json_decode($jsonApi, true);
    if (!empty($dadosApi['results'])) {
        foreach ($dadosApi['results'] as $filme) {
            $tmdbConteudos[] = [
                'id' => $filme['id'],
                'titulo' => $filme['title'] ?? $filme['original_title'],
                'descricao' => $filme['overview'] ?: 'Sem descrição disponível.',
                'imagem_url' => !empty($filme['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $filme['poster_path'] : 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=600&q=80',
                'categoria_nome' => 'TMDB Popular',
                'ano' => !empty($filme['release_date']) ? substr($filme['release_date'], 0, 4) : null,
                'destaque' => 1
            ];
        }
    }
}

// Busca categorias locais
$categorias = $pdo->query("SELECT id, nome FROM categorias ORDER BY nome")->fetchAll();
$filtro = filter_input(INPUT_GET, 'categoria', FILTER_VALIDATE_INT);
$busca = trim($_GET['busca'] ?? '');

// Se a API não retornou nada ou houve erro, usa o banco de dados local como fallback
if (!empty($tmdbConteudos) && !$filtro) {
    $conteudos = $tmdbConteudos;
    if ($busca !== '') {
        $conteudos = array_filter($conteudos, function($item) use ($busca) {
            return mb_stripos($item['titulo'], $busca) !== false || mb_stripos($item['descricao'], $busca) !== false;
        });
    }
} else {
    $sql = "SELECT c.*, cat.nome AS categoria_nome
            FROM conteudos c
            JOIN categorias cat ON cat.id = c.categoria_id
            WHERE 1=1";
    $params = [];

    if ($filtro) {
        $sql .= " AND c.categoria_id = ?";
        $params[] = $filtro;
    }
    if ($busca !== '') {
        $sql .= " AND (c.titulo LIKE ? OR c.descricao LIKE ?)";
        $params[] = "%$busca%";
        $params[] = "%$busca%";
    }
    $sql .= " ORDER BY c.destaque DESC, c.criado_em DESC, c.titulo";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $conteudos = $stmt->fetchAll();
}

$destaque = null;
foreach ($conteudos as $item) {
    if (!empty($item['destaque'])) { $destaque = $item; break; }
}
if (!$destaque && count($conteudos)) $destaque = reset($conteudos);
?>
<!DOCTYPE html>
<html lang="pt-BR">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>LUMEN — Onde a luz encontra as histórias</title>
<link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topbar">
    <a class="logo" href="index.php">LUM<span>E</span>N</a>
    <nav>
        <a class="active" href="index.php">Início</a>
        <a href="index.php#catalogo">Explorar</a>
        <?php if (!empty($_SESSION['usuario_id'])): ?>
            <a href="favoritos.php">Minha lista</a>
        <?php endif; ?>
    </nav>
    <div class="nav-actions">
        <form class="search-form" action="index.php" method="get">
            <?php if ($filtro): ?><input type="hidden" name="categoria" value="<?= (int)$filtro ?>"><?php endif; ?>
            <input type="search" name="busca" placeholder="Buscar títulos..." value="<?= e($busca) ?>">
            <button aria-label="Buscar" type="submit">⌕</button>
        </form>
        <?php if (!empty($_SESSION['usuario_id'])): ?>
            <span class="user-greeting">Olá, <?= e($_SESSION['usuario_nome'] ?? 'assinante') ?></span>
            <a class="login-link" href="logout.php">Sair</a>
        <?php else: ?>
            <a class="login-link" href="login.php">Entrar</a>
            <a class="button button-small" href="cadastro.php">Criar conta</a>
        <?php endif; ?>
    </div>
</header>

<main>
<?php if ($destaque && !$busca && !$filtro): ?>
<section class="hero" style="--hero-image: url('<?= e($destaque['imagem_url'] ?: 'https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=1800&q=85') ?>')">
    <div class="hero-shade"></div>
    <div class="hero-content">
        <span class="eyebrow">DESTAQUE LUMEN · <?= e($destaque['categoria_nome']) ?></span>
        <h1><?= e($destaque['titulo']) ?></h1>
        <p><?= e(mb_strimwidth($destaque['descricao'], 0, 190, '…', 'UTF-8')) ?></p>
        <div class="hero-meta">
            <?php if (!empty($destaque['ano'])): ?><span><?= e($destaque['ano']) ?></span><?php endif; ?>
        </div>
        <div class="hero-buttons">
            <a class="button" href="detalhe.php?id=<?= (int)$destaque['id'] ?>">▶ &nbsp; Explorar título</a>
            <?php if (!empty($_SESSION['usuario_id'])): ?>
                <form action="favorito_acao.php" method="post">
                    <input type="hidden" name="conteudo_id" value="<?= (int)$destaque['id'] ?>">
                    <input type="hidden" name="voltar" value="index.php">
                    <button class="button button-ghost" type="submit">＋ Minha lista</button>
                </form>
            <?php else: ?>
                <a class="button button-ghost" href="login.php">＋ Minha lista</a>
            <?php endif; ?>
        </div>
    </div>
    <div class="hero-bottom"><span>ONDE A LUZ ENCONTRA AS HISTÓRIAS.</span><span>CINEMA · SÉRIES · EXPERIÊNCIAS</span></div>
</section>
<?php else: ?>
<section class="page-intro">
    <span class="eyebrow">CATÁLOGO LUMEN</span>
    <h1><?= $busca ? 'Resultados da busca' : ($filtro ? 'Explore por categoria' : 'Descubra sua próxima história') ?></h1>
    <p><?= $busca ? 'Resultados para “' . e($busca) . '”.' : 'Histórias para todos os momentos, reunidas em um só lugar.' ?></p>
</section>
<?php endif; ?>

<section class="catalog-section" id="catalogo">
    <div class="section-heading">
        <div>
            <span class="eyebrow">ESCOLHA SEU PRÓXIMO FILME</span>
            <h2><?= ($busca || $filtro) ? 'Títulos encontrados' : 'Explore o catálogo' ?></h2>
        </div>
        <span class="result-count"><?= count($conteudos) ?> títulos</span>
    </div>
    <div class="category-pills">
        <a class="<?= !$filtro ? 'selected' : '' ?>" href="index.php<?= $busca ? '?busca=' . urlencode($busca) : '' ?>">Todos (TMDB)</a>
        <?php foreach ($categorias as $cat): ?>
            <a class="<?= $filtro == $cat['id'] ? 'selected' : '' ?>" href="index.php?categoria=<?= (int)$cat['id'] ?><?= $busca ? '&busca=' . urlencode($busca) : '' ?>"><?= e($cat['nome']) ?></a>
        <?php endforeach; ?>
    </div>
    <div class="movie-grid" id="movieGrid">
        <?php foreach ($conteudos as $item): ?>
        <article class="movie-card" data-title="<?= e(mb_strtolower($item['titulo'], 'UTF-8')) ?>" data-category="<?= e(mb_strtolower($item['categoria_nome'], 'UTF-8')) ?>">
            <a class="poster-link" href="detalhe.php?id=<?= (int)$item['id'] ?>">
                <div class="poster">
                    <img loading="lazy" src="<?= e($item['imagem_url']) ?>" alt="Capa de <?= e($item['titulo']) ?>" onerror="this.src='https://images.unsplash.com/photo-1489599849927-2ee91cede3ba?auto=format&fit=crop&w=600&q=80'">
                    <?php if (!empty($item['destaque'])): ?><span class="poster-tag">DESTAQUE</span><?php endif; ?>
                    <span class="poster-play">▶</span>
                </div>
            </a>
            <div class="card-info">
                <div><h3><?= e($item['titulo']) ?></h3><span><?= e($item['categoria_nome']) ?><?= !empty($item['ano']) ? ' · ' . e($item['ano']) : '' ?></span></div>
                <?php if (!empty($_SESSION['usuario_id'])): ?>
                <form action="favorito_acao.php" method="post">
                    <input type="hidden" name="conteudo_id" value="<?= (int)$item['id'] ?>">
                    <input type="hidden" name="voltar" value="index.php">
                    <button class="heart-button" title="Adicionar/remover dos favoritos" type="submit">♡</button>
                </form>
                <?php endif; ?>
            </div>
        </article>
        <?php endforeach; ?>
    </div>
    <?php if (!count($conteudos)): ?>
        <div class="empty-state"><span>⌕</span><h3>Nenhum título encontrado</h3><p>Tente outro termo ou selecione uma categoria diferente.</p><a class="button" href="index.php">Ver catálogo completo</a></div>
    <?php endif; ?>
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