
<?php
require_once __DIR__ . '/config.php';

$busca = trim($_GET['q'] ?? '');

$sql = 'SELECT id, nome_popular, nome_cientifico, familia_botanica, imagem
        FROM plantas';

$params = [];

if ($busca !== '') {
    $sql .= ' WHERE nome_popular LIKE :q
              OR nome_cientifico LIKE :q
              OR familia_botanica LIKE :q
              OR genero LIKE :q';
    $params['q'] = '%' . $busca . '%';
}

$sql .= ' ORDER BY nome_popular ASC';

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$plantas = $stmt->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/svg+xml" href="abelha.svg">
    <title>Inventário Botânico | Catálogo de Plantas</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="catalogo.css">
</head>

<body class="pagina-catalogo">

<header class="catalogo-topo">
    <a class="catalogo-marca" href="index.php">
       <span class="marca-simbolo">🐝</span>
        <span>
            <strong>Inventário Botânico</strong>
            <small>CATÁLOGO FLORÍSTICO · UFC</small>
        </span>
    </a>

    <a class="catalogo-login" href="login.php">
        <span>Área administrativa</span>
        <span aria-hidden="true">↗</span>
    </a>
</header>

<main class="catalogo-principal">

    <section class="catalogo-hero">
        <div class="hero-conteudo">
            <span class="hero-etiqueta">
                <span class="ponto-verde"></span>
                CATÁLOGO DE PLANTAS E POLINIZADORES
            </span>

            <h1>Descubra a riqueza da <em>flora</em>.</h1>

            <p>
                Explore as espécies vegetais do setor de Abelhas
                do Campus do Pici e conheça sua importância
                para os polinizadores.
            </p>

            <a href="#colecao" class="hero-link">
                Explorar coleção <span>↓</span>
            </a>
        </div>

        <div class="hero-decoracao" aria-hidden="true">
    <span class="abelha-decorativa">🐝</span>
    <small>CONHECER · PRESERVAR · PESQUISAR</small>
</div>
    </section>

    <section class="colecao" id="colecao">

        <div class="colecao-cabecalho">
            <div>
                <span class="secao-etiqueta">EXPLORE O ACERVO</span>
                <h2>Conheça nossas espécies</h2>
                <p>Encontre informações sobre as plantas presentes no inventário.</p>
            </div>

            <span class="total-plantas">
                <?= count($plantas) ?>
                <?= count($plantas) === 1 ? 'espécie' : 'espécies' ?>
            </span>
        </div>

        <form class="catalogo-busca" method="get" action="index.php">
            <span class="busca-icone" aria-hidden="true">⌕</span>

            <label class="sr-only" for="q">Buscar uma espécie</label>

            <input
                id="q"
                name="q"
                type="search"
                value="<?= e($busca) ?>"
                placeholder="Busque por nome, família ou gênero..."
            >

            <?php if ($busca !== ''): ?>
                <a class="limpar-busca" href="index.php">Limpar</a>
            <?php endif; ?>

            <button type="submit">Buscar</button>
        </form>

        <?php if ($busca !== ''): ?>
            <p class="catalogo-resultado">
                Resultados para: <strong>“<?= e($busca) ?>”</strong>
            </p>
        <?php endif; ?>

        <?php if ($plantas): ?>
            <div class="catalogo-grade">
                <?php foreach ($plantas as $planta): ?>
                    <a
                        class="planta-card"
                        href="detalhes.php?id=<?= (int)$planta['id'] ?>"
                    >
                        <div class="planta-foto">
                            <?php if (
                                !empty($planta['imagem']) &&
                                is_file(__DIR__ . '/' . $planta['imagem'])
                            ): ?>
                                <img
                                    src="<?= e($planta['imagem']) ?>"
                                    alt="<?= e($planta['nome_popular']) ?>"
                                    loading="lazy"
                                >
                            <?php else: ?>
                                <div class="planta-sem-foto">
                                    <span>❧</span>
                                    <small>Imagem não disponível</small>
                                </div>
                            <?php endif; ?>

                            <span class="foto-selo">ESPÉCIE BOTÂNICA</span>
                        </div>

                        <div class="planta-informacoes">
                            <span class="planta-familia">
                                <?= e($planta['familia_botanica'] ?: 'Família não informada') ?>
                            </span>

                            <h3><?= e($planta['nome_popular']) ?></h3>

                            <p class="planta-nome-cientifico">
                                <?= e($planta['nome_cientifico']) ?>
                            </p>

                            <span class="planta-ver-ficha">
                                Conhecer espécie <span>↗</span>
                            </span>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>

        <?php else: ?>
            <div class="catalogo-vazio">
                <span aria-hidden="true">❧</span>
                <h3>Nenhuma planta encontrada</h3>

                <p>
                    <?= $busca
                        ? 'Não encontramos resultados para sua pesquisa. Tente outra palavra.'
                        : 'As plantas cadastradas aparecerão aqui.' ?>
                </p>

                <?php if ($busca !== ''): ?>
                    <a href="index.php">Limpar pesquisa</a>
                <?php else: ?>
                    <a href="login.php">Acessar área administrativa</a>
                <?php endif; ?>
            </div>
        <?php endif; ?>

    </section>

    <section class="catalogo-nota">
        <span aria-hidden="true">✳</span>
        <p>
            <strong>Conhecimento que floresce.</strong>
            Um acervo dedicado à pesquisa, à biodiversidade e à educação ambiental.
        </p>
    </section>

</main>

<footer class="catalogo-rodape">
    <a href="index.php">Inventário Botânico</a>
    <span>Campus do Pici · Universidade Federal do Ceará</span>
</footer>

</body>
</html>
