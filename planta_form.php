<?php
require_once __DIR__ . '/config.php';
exigir_admin();
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
$planta = [
'nome_popular'=>'','nome_cientifico'=>'','familia_botanica'=>'','genero'=>'','especie'=>'',
'habito'=>'','origem'=>'','recursos_oferecidos'=>'','abelhas_atraidas'=>'',
'produtos_para_abelhas'=>'','observacoes'=>'','imagem'=>null
];
if ($id) {
    $stmt = $pdo->prepare('SELECT * FROM plantas WHERE id = ?');
    $stmt->execute([$id]);
    $achada = $stmt->fetch();
    if (!$achada) { http_response_code(404); exit('Planta não encontrada.'); }
    $planta = $achada;
}
$erro = $_SESSION['form_erro'] ?? '';
unset($_SESSION['form_erro']);
?>
<!doctype html><html lang="pt-BR"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width, initial-scale=1"><title><?= $id ? 'Editar planta' : 'Nova planta' ?></title><link rel="stylesheet" href="style.css"></head>
<body><header class="topo"><a class="marca" href="index.php">Inventário Botânico</a><a class="voltar" href="admin.php">← Voltar ao painel</a></header>
<main class="container form-pagina"><p class="sobretitulo">FORMULÁRIO DE CADASTRO</p><h1><?= $id ? 'Editar planta' : 'Cadastrar nova planta' ?></h1><p>Preencha os dados conhecidos. Os campos complementares podem ficar vazios e ser atualizados depois.</p>
<?php if ($erro): ?><div class="alerta"><?= e($erro) ?></div><?php endif; ?>
<form class="formulario formulario-planta" method="post" action="salvar_planta.php" enctype="multipart/form-data">
<input type="hidden" name="csrf_token" value="<?= e(csrf_token()) ?>"><input type="hidden" name="id" value="<?= (int)($id ?? 0) ?>">
<div class="campo"><label for="nome_popular">Nome popular *</label><input id="nome_popular" name="nome_popular" required maxlength="180" value="<?= e($planta['nome_popular']) ?>"></div>
<div class="campo"><label for="nome_cientifico">Nome científico *</label><input id="nome_cientifico" name="nome_cientifico" required maxlength="220" value="<?= e($planta['nome_cientifico']) ?>"></div>
<div class="campo"><label for="familia_botanica">Família botânica</label><input id="familia_botanica" name="familia_botanica" value="<?= e($planta['familia_botanica']) ?>"></div>
<div class="campo"><label for="genero">Gênero</label><input id="genero" name="genero" value="<?= e($planta['genero']) ?>"></div>
<div class="campo"><label for="especie">Espécie</label><input id="especie" name="especie" value="<?= e($planta['especie']) ?>"></div>
<div class="campo"><label for="habito">Hábito</label><input id="habito" name="habito" placeholder="Ex.: árvore, arbusto, erva..." value="<?= e($planta['habito']) ?>"></div>
<div class="campo campo-largo"><label for="origem">Origem</label><input id="origem" name="origem" placeholder="Ex.: nativa do Brasil..." value="<?= e($planta['origem']) ?>"></div>
<div class="campo campo-largo"><label for="recursos_oferecidos">Recursos oferecidos</label><textarea id="recursos_oferecidos" name="recursos_oferecidos" rows="3" placeholder="Néctar, pólen, resina, óleo floral..."><?= e($planta['recursos_oferecidos']) ?></textarea></div>
<div class="campo campo-largo"><label for="abelhas_atraidas">Abelhas atraídas</label><textarea id="abelhas_atraidas" name="abelhas_atraidas" rows="3" placeholder="Espécies ou grupos de abelhas visitantes, se houver fonte confiável."><?= e($planta['abelhas_atraidas']) ?></textarea></div>
<div class="campo campo-largo"><label for="produtos_para_abelhas">Produtos/recursos para as abelhas</label><textarea id="produtos_para_abelhas" name="produtos_para_abelhas" rows="3" placeholder="O que as abelhas coletam e como esses recursos podem ser usados na colônia."><?= e($planta['produtos_para_abelhas']) ?></textarea></div>
<div class="campo campo-largo"><label for="observacoes">Observações</label><textarea id="observacoes" name="observacoes" rows="4" placeholder="Época de floração, ocorrência regional, referências..."><?= e($planta['observacoes']) ?></textarea></div>
<div class="campo campo-largo"><label for="imagem">Imagem da planta (JPG, PNG ou WebP; até 5 MB)</label><input id="imagem" name="imagem" type="file" accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"><?php if (!empty($planta['imagem'])): ?><p class="ajuda">Imagem atual: <?= e(basename($planta['imagem'])) ?>. Envie outra apenas se quiser substituí-la.</p><?php endif; ?></div>
<div class="campo campo-largo botoes-form"><button class="botao botao-verde" type="submit">Salvar planta</button><a class="botao botao-claro" href="admin.php">Cancelar</a></div>
</form></main></body></html>
