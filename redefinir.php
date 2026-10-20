<?php
require 'config.php';
$mensagem = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $stmt = $pdo->prepare("SELECT id FROM usuarios WHERE email=?");
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $mensagem = 'Encontramos o cadastro. Em um serviço real, você receberia um e-mail com as instruções para redefinir a senha.';
        } else {
            $mensagem = 'Se existir uma conta com esse e-mail, as instruções serão enviadas.';
        }
    } else {
        $mensagem = 'Digite um e-mail válido.';
    }
}
?>
<!DOCTYPE html><html lang="pt-BR"><head><meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0"><title>Redefinir senha — LUMEN</title><link rel="stylesheet" href="style.css"></head><body class="auth-body"><a class="logo auth-logo" href="index.php">LUM<span>E</span>N</a><main class="auth-card"><span class="eyebrow">RECUPERAÇÃO DE ACESSO</span><h1>Esqueceu a senha?</h1><p>Informe o e-mail utilizado no cadastro para simular o pedido de redefinição.</p><?php if ($mensagem): ?><div class="alert success"><?= e($mensagem) ?></div><?php endif; ?><form method="post" class="auth-form"><label for="email">E-mail cadastrado</label><input id="email" name="email" type="email" required><button class="button button-full" type="submit">Solicitar redefinição</button></form><a class="back-link" href="login.php">← Voltar para o login</a></main></body></html>
