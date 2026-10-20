<?php
require 'config.php';
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $stmt = $pdo->prepare("SELECT id,nome,email,senha FROM usuarios WHERE email=? LIMIT 1");
    $stmt->execute([$email]);
    $usuario = $stmt->fetch();
    if ($usuario && password_verify($senha, $usuario['senha'])) {
        session_regenerate_id(true);
        $_SESSION['usuario_id'] = $usuario['id'];
        $_SESSION['usuario_nome'] = $usuario['nome'];
        $_SESSION['usuario_email'] = $usuario['email'];
        header('Location: index.php');
        exit;
    }
    $erro = 'E-mail ou senha incorretos.';
}
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Entrar — LUMEN</title><link rel="stylesheet" href="style.css"></head><body class="auth-body"><a class="logo auth-logo" href="index.php">LUM<span>E</span>N</a><main class="auth-card"><span class="eyebrow">BEM-VINDO DE VOLTA</span><h1>Entre no LUMEN</h1><p>Continue descobrindo histórias que ficam.</p><?php if ($erro): ?><div class="alert error"><?= e($erro) ?></div><?php endif; ?><form method="post" class="auth-form"><label for="email">E-mail</label><input id="email" name="email" type="email" required autocomplete="email"><label for="senha">Senha</label><input id="senha" name="senha" type="password" required autocomplete="current-password"><button class="button button-full" type="submit">Entrar</button></form><a class="forgot-link" href="redefinir.php">Esqueci minha senha</a><p class="auth-switch">Ainda não tem conta? <a href="cadastro.php">Criar conta</a></p><a class="back-link" href="index.php">← Voltar para o catálogo</a></main></body></html>
