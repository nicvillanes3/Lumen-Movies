<?php
require 'config.php';
exigirLogin();
$erro = '';
$sucesso = '';
// CRUD de demonstração protegido por login. Para produção, crie uma permissão de administrador.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $acao = $_POST['acao'] ?? '';
    if ($acao === 'excluir') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
        if ($id) {
            $stmt = $pdo->prepare("DELETE FROM conteudos WHERE id=?");
            $stmt->execute([$id]);
            $sucesso = 'Conteúdo excluído.';
        }
    } elseif ($acao === 'salvar') {
        $id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: null;
        $categoriaId = filter_input(INPUT_POST, 'categoria_id', FILTER_VALIDATE_INT);
        $titulo = trim($_POST['titulo'] ?? '');
        $descricao = trim($_POST['descricao'] ?? '');
        $imagem = trim($_POST['imagem_url'] ?? '');
        $ano = filter_input(INPUT_POST, 'ano', FILTER_VALIDATE_INT) ?: null;
        $classificacao = trim($_POST['classificacao'] ?? '');
        $duracao = filter_input(INPUT_POST, 'duracao_minutos', FILTER_VALIDATE_INT) ?: null;
        $destaque = isset($_POST['destaque']) ? 1 : 0;
        if (!$categoriaId || $titulo === '' || $descricao === '') {
            $erro = 'Categoria, título e descrição são obrigatórios.';
        } else {
            if ($id) {
                $stmt = $pdo->prepare("UPDATE conteudos SET categoria_id=?,titulo=?,descricao=?,imagem_url=?,ano=?,classificacao=?,duracao_minutos=?,destaque=? WHERE id=?");
                $stmt->execute([$categoriaId,$titulo,$descricao,$imagem ?: null,$ano,$classificacao ?: null,$duracao,$destaque,$id]);
                $sucesso = 'Conteúdo atualizado.';
            } else {
                $stmt = $pdo->prepare("INSERT INTO conteudos (categoria_id,titulo,descricao,imagem_url,ano,classificacao,duracao_minutos,destaque) VALUES (?,?,?,?,?,?,?,?)");
                $stmt->execute([$categoriaId,$titulo,$descricao,$imagem ?: null,$ano,$classificacao ?: null,$duracao,$destaque]);
                $sucesso = 'Conteúdo cadastrado.';
            }
        }
    }
}
$categorias = $pdo->query("SELECT id,nome FROM categorias ORDER BY nome")->fetchAll();
$editar = null;
$editId = filter_input(INPUT_GET, 'editar', FILTER_VALIDATE_INT);
if ($editId) {
    $stmt = $pdo->prepare("SELECT * FROM conteudos WHERE id=?");
    $stmt->execute([$editId]);
    $editar = $stmt->fetch();
}
$conteudos = $pdo->query("SELECT c.id,c.titulo,c.ano,c.destaque,cat.nome categoria_nome FROM conteudos c JOIN categorias cat ON cat.id=c.categoria_id ORDER BY c.criado_em DESC")->fetchAll();
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Gerenciar catálogo — LUMEN</title><link rel="stylesheet" href="style.css"></head><body><header class="topbar"><a class="logo" href="index.php">LUM<span>E</span>N</a><nav><a href="index.php">Ver catálogo</a><a class="active" href="admin_conteudos.php">Gerenciar conteúdo</a></nav><div class="nav-actions"><a class="login-link" href="logout.php">Sair</a></div></header><main class="admin-page"><span class="eyebrow">PAINEL DE GERENCIAMENTO</span><h1>Gerenciar catálogo</h1><p class="admin-warning">Área demonstrativa: nesta versão, qualquer usuário logado consegue acessar. Em produção, restrinja o acesso a administradores.</p><?php if ($erro): ?><div class="alert error"><?= e($erro) ?></div><?php endif; ?><?php if ($sucesso): ?><div class="alert success"><?= e($sucesso) ?></div><?php endif; ?><section class="admin-form-card"><h2><?= $editar ? 'Editar conteúdo' : 'Adicionar conteúdo' ?></h2><form method="post" class="admin-form"><input type="hidden" name="acao" value="salvar"><input type="hidden" name="id" value="<?= (int)($editar['id'] ?? 0) ?>"><label>Categoria<select name="categoria_id" required><option value="">Selecione</option><?php foreach ($categorias as $cat): ?><option value="<?= (int)$cat['id'] ?>" <?= ($editar['categoria_id'] ?? '') == $cat['id'] ? 'selected' : '' ?>><?= e($cat['nome']) ?></option><?php endforeach; ?></select></label><label>Título<input name="titulo" required maxlength="180" value="<?= e($editar['titulo'] ?? '') ?>"></label><label>Descrição<textarea name="descricao" required rows="4"><?= e($editar['descricao'] ?? '') ?></textarea></label><label>URL da imagem<input type="url" name="imagem_url" maxlength="500" value="<?= e($editar['imagem_url'] ?? '') ?>" placeholder="https://..."></label><div class="admin-fields"><label>Ano<input type="number" name="ano" min="1888" max="2100" value="<?= e($editar['ano'] ?? '') ?>"></label><label>Classificação<input name="classificacao" maxlength="20" value="<?= e($editar['classificacao'] ?? '') ?>" placeholder="Livre, 12, 16..."></label><label>Duração (minutos)<input type="number" name="duracao_minutos" min="1" max="65535" value="<?= e($editar['duracao_minutos'] ?? '') ?>"></label></div><label class="check-label"><input type="checkbox" name="destaque" value="1" <?= !empty($editar['destaque']) ? 'checked' : '' ?>> Destacar na página inicial</label><button class="button" type="submit"><?= $editar ? 'Salvar alterações' : 'Cadastrar conteúdo' ?></button><?php if ($editar): ?><a class="back-link" href="admin_conteudos.php">Cancelar edição</a><?php endif; ?></form></section><section class="admin-table-wrap"><h2>Conteúdos cadastrados (<?= count($conteudos) ?>)</h2><div class="admin-table-scroll"><table class="admin-table"><thead><tr><th>Título</th><th>Categoria</th><th>Ano</th><th>Destaque</th><th>Ações</th></tr></thead><tbody><?php foreach ($conteudos as $item): ?><tr><td><?= e($item['titulo']) ?></td><td><?= e($item['categoria_nome']) ?></td><td><?= e($item['ano'] ?? '—') ?></td><td><?= $item['destaque'] ? 'Sim' : 'Não' ?></td><td class="table-actions"><a href="admin_conteudos.php?editar=<?= (int)$item['id'] ?>">Editar</a><form method="post" onsubmit="return confirm('Excluir este conteúdo?')"><input type="hidden" name="acao" value="excluir"><input type="hidden" name="id" value="<?= (int)$item['id'] ?>"><button type="submit">Excluir</button></form></td></tr><?php endforeach; ?></tbody></table></div></section></main><footer class="footer"><a class="logo" href="index.php">LUM<span>E</span>N</a><p>Onde a luz encontra as histórias.</p></footer></body></html>
