<?php
require_once __DIR__ . '/config.php';
if (!empty($_SESSION['admin_id'])) {
    header('Location: admin.php');
    exit;
}
$erro = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    validar_csrf();
    $email = trim($_POST['email'] ?? '');
    $senha = $_POST['senha'] ?? '';
    $stmt = $pdo->prepare('SELECT id, nome, email, senha_hash FROM administradores WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($senha, $admin['senha_hash'])) {
        session_regenerate_id(true);
        $_SESSION['admin_id'] = (int)$admin['id'];
        $_SESSION['admin_nome'] = $admin['nome'];
        header('Location: admin.php');
        exit;
    }
    $erro = 'E-mail ou senha incorretos.';
}
?>
<!doctype html>
<html lang="pt-BR"><head>
<meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Login administrativo</title><link rel="stylesheet" href="style.css"></head>
<body class="fundo-login">
<header class="topo"><a class="marca" href="index.php">Inventário Botânico</a><a class="voltar" href="index.php">← Voltar ao catálogo</a></header>
<main class="login-caixa">
  <span class="icone-login">🌱</span><h1>Área administrativa</h1>
  <p>Entre com a conta autorizada para gerenciar o inventário.</p>
  <?php if ($erro): ?><div class="alerta"><?= e($erro) ?></div><?php endif; ?>
  <form method="post" class="formulario">
    <input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>">
    <label for="email">E-mail</label><input id="email" name="email" type="email" required autocomplete="username">
    <label for="senha">Senha</label><input id="senha" name="senha" type="password" required autocomplete="current-password">
    <button class="botao botao-verde largura-total" type="submit">Entrar</button>
  </form>
</main>
</body></html>
