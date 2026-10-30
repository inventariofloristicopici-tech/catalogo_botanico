<?php
require_once __DIR__ . '/config.php';
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$stmt = $pdo->prepare('SELECT * FROM plantas WHERE id = ?');
$stmt->execute([$id ?: 0]);
$planta = $stmt->fetch();
if (!$planta) {
    http_response_code(404);
    exit('Planta não encontrada. <a href="index.php">Voltar ao inventário</a>');
}
$campos = [
    'Nome científico' => $planta['nome_cientifico'],
    'Nome popular' => $planta['nome_popular'],
    'Família botânica' => $planta['familia_botanica'],
    'Gênero' => $planta['genero'],
    'Espécie' => $planta['especie'],
    'Hábito' => $planta['habito'],
    'Origem' => $planta['origem'],
    'Recursos oferecidos' => $planta['recursos_oferecidos'],
    'Abelhas atraídas' => $planta['abelhas_atraidas'],
    'Produtos/recursos para as abelhas' => $planta['produtos_para_abelhas'],
    'Observações' => $planta['observacoes'],
];
?>
<!doctype html>
<html lang="pt-BR">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title><?= e($planta['nome_popular']) ?> | Catálogo Botânico</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<header class="topo">
  <a class="marca" href="index.php">Inventário Botânico</a>
  <a class="botao botao-contorno" href="login.php">Entrar como administrador</a>
</header>
<main class="container">
  <a class="voltar" href="index.php">← Voltar ao inventário</a>
  <section class="detalhe-cabecalho">
    <div class="detalhe-foto">
      <?php if (!empty($planta['imagem']) && is_file(__DIR__ . '/' . $planta['imagem'])): ?>
        <img src="<?= e($planta['imagem']) ?>" alt="<?= e($planta['nome_popular']) ?>">
      <?php else: ?><div class="imagem-vazia"><span>🌿</span><small>Imagem não cadastrada</small></div><?php endif; ?>
    </div>
    <div>
      <span class="sobretitulo">FICHA BOTÂNICA</span>
      <h1><?= e($planta['nome_popular']) ?></h1>
      <p class="cientifico grande"><?= e($planta['nome_cientifico']) ?></p>
      <p class="texto-intro">Informações botânicas e recursos relacionados às abelhas.</p>
    </div>
  </section>
  <section class="ficha">
    <h2>Informações da espécie</h2>
    <div class="lista-detalhes">
      <?php foreach ($campos as $titulo => $valor): ?>
        <?php if (trim((string)$valor) !== ''): ?>
          <article class="campo-detalhe">
            <h3><?= e($titulo) ?></h3>
            <p><?= nl2br(e($valor)) ?></p>
          </article>
        <?php endif; ?>
      <?php endforeach; ?>
    </div>
  </section>
</main>
<footer class="rodape">Catálogo Botânico · Informação para pesquisa e educação ambiental</footer>
</body>
</html>
