<?php
require_once __DIR__ . '/config.php';
exigir_admin();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { header('Location: admin.php'); exit; }
validar_csrf();

$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT) ?: 0;
$campos = ['nome_popular','nome_cientifico','familia_botanica','genero','especie','habito','origem','recursos_oferecidos','abelhas_atraidas','produtos_para_abelhas','observacoes'];
$dados = [];
foreach ($campos as $campo) { $dados[$campo] = trim($_POST[$campo] ?? ''); }
if ($dados['nome_popular'] === '' || $dados['nome_cientifico'] === '') {
    $_SESSION['form_erro'] = 'Nome popular e nome científico são obrigatórios.';
    header('Location: planta_form.php' . ($id ? '?id='.$id : '')); exit;
}
$imagemAtual = null;
if ($id) {
    $stmt = $pdo->prepare('SELECT imagem FROM plantas WHERE id = ?');
    $stmt->execute([$id]);
    $registro = $stmt->fetch();
    if (!$registro) { exit('Planta não encontrada.'); }
    $imagemAtual = $registro['imagem'];
}
$imagem = $imagemAtual;
if (isset($_FILES['imagem']) && $_FILES['imagem']['error'] !== UPLOAD_ERR_NO_FILE) {
    if ($_FILES['imagem']['error'] !== UPLOAD_ERR_OK || $_FILES['imagem']['size'] > 5 * 1024 * 1024) {
        $_SESSION['form_erro'] = 'O upload falhou ou a imagem ultrapassa 5 MB.';
        header('Location: planta_form.php' . ($id ? '?id='.$id : '')); exit;
    }
    $tmp = $_FILES['imagem']['tmp_name'];
    $finfo = new finfo(FILEINFO_MIME_TYPE);
    $mime = $finfo->file($tmp);
    $extensoes = ['image/jpeg'=>'jpg','image/png'=>'png','image/webp'=>'webp'];
    if (!isset($extensoes[$mime]) || !getimagesize($tmp)) {
        $_SESSION['form_erro'] = 'Formato inválido. Use JPG, PNG ou WebP.';
        header('Location: planta_form.php' . ($id ? '?id='.$id : '')); exit;
    }
    $pasta = __DIR__ . '/uploads';
    if (!is_dir($pasta)) { mkdir($pasta, 0755, true); }
    $nomeArquivo = bin2hex(random_bytes(16)) . '.' . $extensoes[$mime];
    if (!move_uploaded_file($tmp, $pasta . '/' . $nomeArquivo)) {
        $_SESSION['form_erro'] = 'Não foi possível salvar a imagem. Confira a permissão da pasta uploads.';
        header('Location: planta_form.php' . ($id ? '?id='.$id : '')); exit;
    }
    $imagem = 'uploads/' . $nomeArquivo;
}
if ($id) {
    $sql = 'UPDATE plantas SET nome_popular=:nome_popular, nome_cientifico=:nome_cientifico,
      familia_botanica=:familia_botanica, genero=:genero, especie=:especie, habito=:habito,
      origem=:origem, recursos_oferecidos=:recursos_oferecidos, abelhas_atraidas=:abelhas_atraidas,
      produtos_para_abelhas=:produtos_para_abelhas, observacoes=:observacoes, imagem=:imagem WHERE id=:id';
    $dados['imagem'] = $imagem; $dados['id'] = $id;
    $stmt = $pdo->prepare($sql); $stmt->execute($dados);
} else {
    $sql = 'INSERT INTO plantas (nome_popular,nome_cientifico,familia_botanica,genero,especie,habito,origem,recursos_oferecidos,abelhas_atraidas,produtos_para_abelhas,observacoes,imagem)
      VALUES (:nome_popular,:nome_cientifico,:familia_botanica,:genero,:especie,:habito,:origem,:recursos_oferecidos,:abelhas_atraidas,:produtos_para_abelhas,:observacoes,:imagem)';
    $dados['imagem'] = $imagem;
    $stmt = $pdo->prepare($sql); $stmt->execute($dados);
}
if ($imagemAtual && $imagem && $imagemAtual !== $imagem && str_starts_with($imagemAtual, 'uploads/')) {
    $antiga = __DIR__ . '/' . $imagemAtual;
    if (is_file($antiga)) { unlink($antiga); }
}
header('Location: admin.php');
exit;
