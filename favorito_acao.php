<?php
require 'config.php';
exigirLogin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: index.php');
    exit;
}

$conteudoId = filter_input(INPUT_POST, 'conteudo_id', FILTER_VALIDATE_INT);
$voltar = $_POST['voltar'] ?? 'index.php';

// Validação segura do redirecionamento
if (!preg_match('/^(index\.php|favoritos\.php|detalhe\.php\?id=\d+)$/', $voltar)) {
    $voltar = 'index.php';
}

if ($conteudoId && !empty($_SESSION['usuario_id'])) {
    $usuarioId = $_SESSION['usuario_id'];

    // 1. Verifica se o filme existe na tabela local 'conteudos'
    $stmtCheck = $pdo->prepare("SELECT id FROM conteudos WHERE id = ?");
    $stmtCheck->execute([$conteudoId]);
    $existeLocal = $stmtCheck->fetch();

    // 2. Se não existir localmente (é um filme da API do TMDB), vamos criá-lo automaticamente na base de dados local
    if (!$existeLocal) {
        $apiUrl = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://" . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/tmdb_api.php?action=popular';
        $jsonApi = @file_get_contents($apiUrl);
        
        if ($jsonApi) {
            $dadosApi = json_decode($jsonApi, true);
            if (!empty($dadosApi['results'])) {
                foreach ($dadosApi['results'] as $filme) {
                    if ($filme['id'] == $conteudoId) {
                        // Garante que existe uma categoria padrão (ex: ID 1 ou cria se precisar)
                        $catStmt = $pdo->query("SELECT id FROM categorias LIMIT 1");
                        $categoriaId = $catStmt->fetchColumn() ?: 1;

                        $titulo = $filme['title'] ?? $filme['original_title'];
                        $descricao = $filme['overview'] ?: 'Sem descrição.';
                        $imagemUrl = !empty($filme['poster_path']) ? 'https://image.tmdb.org/t/p/w500' . $filme['poster_path'] : null;
                        $ano = !empty($filme['release_date']) ? substr($filme['release_date'], 0, 4) : null;

                        // Insere o filme do TMDB na tabela local para permitir o relacionamento de favoritos
                        $insFilme = $pdo->prepare("INSERT IGNORE INTO conteudos (id, categoria_id, titulo, descricao, imagem_url, ano, destaque) VALUES (?, ?, ?, ?, ?, ?, 1)");
                        $insFilme->execute([$conteudoId, $categoriaId, $titulo, $descricao, $imagemUrl, $ano]);
                        break;
                    }
                }
            }
        }
    }

    // 3. Alterna o estado dos favoritos (adiciona ou remove)
    $stmtFav = $pdo->prepare("SELECT id FROM favoritos WHERE usuario_id=? AND conteudo_id=?");
    $stmtFav->execute([$usuarioId, $conteudoId]);
    
    if ($stmtFav->fetch()) {
        $del = $pdo->prepare("DELETE FROM favoritos WHERE usuario_id=? AND conteudo_id=?");
        $del->execute([$usuarioId, $conteudoId]);
    } else {
        $ins = $pdo->prepare("INSERT IGNORE INTO favoritos (usuario_id, conteudo_id) VALUES (?, ?)");
        $ins->execute([$usuarioId, $conteudoId]);
    }
}

header('Location: ' . $voltar);
exit;
?>