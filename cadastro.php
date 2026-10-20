<?php
require 'config.php';
$erro = '';
$sucesso = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nome = trim($_POST['nome'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    if ($nome === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($senha) < 8) {
        $erro = 'Preencha os campos corretamente. A senha deve ter pelo menos 8 caracteres.';
    } else {
        try {
            $hash = password_hash($senha, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("INSERT INTO usuarios (nome,email,senha) VALUES (?,?,?)");
            $stmt->execute([$nome, $email, $hash]);
            $sucesso = 'Conta criada! Agora você já pode entrar.';
        } catch (PDOException $e) {
            $erro = $e->getCode() === '23000' ? 'Este e-mail já está cadastrado.' : 'Não foi possível criar a conta.';
        }
    }
}
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Criar conta — LUMEN</title><link rel="stylesheet" href="style.css"></head><body class="auth-body"><a class="logo auth-logo" href="index.php">LUM<span>E</span>N</a><main class="auth-card"><span class="eyebrow">ENTRE PARA O UNIVERSO LUMEN</span><h1>Crie sua conta</h1><p>Salve seus títulos favoritos e monte sua própria lista.</p><?php if ($erro): ?><div class="alert error"><?= e($erro) ?></div><?php endif; ?><?php if ($sucesso): ?><div class="alert success"><?= e($sucesso) ?></div><?php endif; ?><form method="post" class="auth-form"><label for="nome">Nome</label><input id="nome" name="nome" required maxlength="100" value="<?= e($_POST['nome'] ?? '') ?>"><label for="email">E-mail</label><input id="email" name="email" type="email" required maxlength="150" value="<?= e($_POST['email'] ?? '') ?>"><label for="senha">Senha</label><input id="senha" name="senha" type="password" minlength="8" required><small>Use pelo menos 8 caracteres.</small><button class="button button-full" type="submit">Criar conta</button></form><p class="auth-switch">Já tem conta? <a href="login.php">Entrar</a></p><a class="back-link" href="index.php">← Voltar para o catálogo</a></main></body></html>
