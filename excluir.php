<?php
require_once __DIR__ . '/config.php';
exigir_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin.php'); exit; }
validar_csrf();
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT imagem FROM plantas WHERE id = ?');
$stmt->execute([$id ?: 0]);
$p = $stmt->fetch();
if ($p) {
    $del = $pdo->prepare('DELETE FROM plantas WHERE id = ?');
    $del->execute([$id]);
    if (!empty($p['imagem']) && str_starts_with($p['imagem'], 'uploads/')) {
        $arquivo = __DIR__ . '/' . $p['imagem'];
        if (is_file($arquivo)) { unlink($arquivo); }
    }
}
header('Location: admin.php');
exit;
