
<?php
require_once __DIR__ . '/config.php';
exigir_admin();

$plantas = $pdo->query(
    'SELECT id, nome_popular, nome_cientifico, familia_botanica, atualizado_em
     FROM plantas
     ORDER BY atualizado_em DESC'
)->fetchAll();
?>
<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Painel administrativo | Inventário Botânico</title>

    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="admin.css">
</head>

<body class="pagina-admin">

<div class="dashboard">

    <!-- MENU LATERAL -->
    <aside class="sidebar">

        <a href="index.php" class="sidebar-marca">
            <span class="sidebar-logo">🌿</span>
            <span>
                <strong>Inventário</strong>
                <small>BOTÂNICO · UFC</small>
            </span>
        </a>

        <div class="sidebar-separador"></div>

        <p class="sidebar-legenda">MENU PRINCIPAL</p>

        <nav class="sidebar-menu">
            <a href="admin.php" class="menu-link ativo">
                <span>▦</span>
                Visão geral
            </a>

            <a href="planta_form.php" class="menu-link">
                <span>＋</span>
                Cadastrar planta
            </a>

            <a href="index.php" class="menu-link">
                <span>⌕</span>
                Ver catálogo
            </a>
        </nav>

        <div class="sidebar-final">
            <div class="sidebar-perfil">
                <span class="perfil-icone">♧</span>
                <div>
                    <strong><?= e($_SESSION['admin_nome'] ?? 'Administrador') ?></strong>
                    <small>Administrador</small>
                </div>
            </div>

            <a href="logout.php" class="menu-sair">
                ↪ Sair da conta
            </a>
        </div>

    </aside>

    <!-- CONTEÚDO PRINCIPAL -->
    <main class="dashboard-conteudo">

        <header class="dashboard-topo">
            <div>
                <span class="topo-indicador">INVENTÁRIO FLORÍSTICO</span>
                <p>Área administrativa</p>
            </div>

            <a href="index.php" class="topo-catalogo">
                Ver catálogo ↗
            </a>
        </header>

        <div class="dashboard-corpo">

            <!-- APRESENTAÇÃO -->
            <section class="dashboard-intro">
                <div>
                    <span class="dashboard-tag">GERENCIAMENTO</span>
                    <h1>Painel administrativo</h1>
                    <p>
                        Organize, consulte e mantenha atualizadas
                        as informações do inventário florístico.
                    </p>
                </div>

                <a href="planta_form.php" class="admin-nova-planta">
                    <span>＋</span> Nova planta
                </a>
            </section>

            <!-- RESUMO -->
            <section class="dashboard-estatisticas">

                <article class="estatistica-card destaque">
                    <div class="estatistica-topo">
                        <span>Total de registros</span>
                        <span class="estatistica-icone">🌿</span>
                    </div>

                    <strong><?= count($plantas) ?></strong>

                    <small>Plantas cadastradas no inventário</small>
                </article>

                <article class="estatistica-card">
                    <div class="estatistica-topo">
                        <span>Acervo botânico</span>
                        <span class="estatistica-icone">♧</span>
                    </div>

                    <strong>UFC</strong>

                    <small>Campus do Pici · Setor de Abelhas</small>
                </article>

                <article class="estatistica-card">
                    <div class="estatistica-topo">
                        <span>Situação</span>
                        <span class="estatistica-icone">✓</span>
                    </div>

                    <strong class="estatistica-status">Ativo</strong>

                    <small>Inventário disponível para gerenciamento</small>
                </article>

            </section>

            <!-- LISTAGEM -->
            <section class="gerenciamento">

                <div class="gerenciamento-cabecalho">
                    <div>
                        <span class="dashboard-tag">ACERVO</span>
                        <h2>Plantas cadastradas</h2>
                        <p>Consulte e gerencie os registros existentes.</p>
                    </div>

                    <span class="contador-registros">
                        <?= count($plantas) ?> registros
                    </span>
                </div>

                <?php if ($plantas): ?>

                    <!-- TABELA PARA COMPUTADORES -->
                    <div class="admin-tabela">
                        <table>
                            <thead>
                                <tr>
                                    <th>Nome popular</th>
                                    <th>Nome científico</th>
                                    <th>Família botânica</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php foreach ($plantas as $p): ?>
                                    <tr>
                                        <td>
                                            <div class="nome-planta">
                                                <span class="mini-folha">🌿</span>
                                                <strong><?= e($p['nome_popular']) ?></strong>
                                            </div>
                                        </td>

                                        <td>
                                            <em><?= e($p['nome_cientifico']) ?></em>
                                        </td>

                                        <td>
                                            <?= e($p['familia_botanica'] ?: 'Não informada') ?>
                                        </td>

                                        <td>
                                            <div class="acoes-linha">
                                                <a href="detalhes.php?id=<?= (int)$p['id'] ?>"
                                                   target="_blank" rel="noopener"
                                                   class="acao-visualizar">
                                                    Ver
                                                </a>

                                                <a href="planta_form.php?id=<?= (int)$p['id'] ?>"
                                                   class="acao-editar">
                                                    Editar
                                                </a>

                                                <form method="post" action="excluir.php"
                                                      onsubmit="return confirm('Tem certeza que deseja excluir esta planta?')">
                                                    <input type="hidden" name="csrf_token"
                                                           value="<?= e(csrf_token()) ?>">
                                                    <input type="hidden" name="id"
                                                           value="<?= (int)$p['id'] ?>">

                                                    <button class="acao-excluir" type="submit">
                                                        Excluir
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- CARTÕES PARA CELULARES -->
                    <div class="admin-cartoes">

                        <?php foreach ($plantas as $p): ?>
                            <article class="admin-cartao">

                                <div class="cartao-planta-topo">
                                    <span class="cartao-folha">🌿</span>
                                    <span>REGISTRO BOTÂNICO</span>
                                </div>

                                <h3><?= e($p['nome_popular']) ?></h3>

                                <p class="cartao-cientifico">
                                    <em><?= e($p['nome_cientifico']) ?></em>
                                </p>

                                <div class="cartao-familia">
                                    <small>FAMÍLIA BOTÂNICA</small>
                                    <strong>
                                        <?= e($p['familia_botanica'] ?: 'Não informada') ?>
                                    </strong>
                                </div>

                                <div class="cartao-acoes">

                                    <a href="detalhes.php?id=<?= (int)$p['id'] ?>"
                                       target="_blank" rel="noopener"
                                       class="acao-mobile visualizar">
                                        Visualizar
                                    </a>

                                    <a href="planta_form.php?id=<?= (int)$p['id'] ?>"
                                       class="acao-mobile editar">
                                        Editar
                                    </a>

                                    <form method="post" action="excluir.php"
                                          onsubmit="return confirm('Tem certeza que deseja excluir esta planta?')">
                                        <input type="hidden" name="csrf_token"
                                               value="<?= e(csrf_token()) ?>">
                                        <input type="hidden" name="id"
                                               value="<?= (int)$p['id'] ?>">

                                        <button type="submit" class="acao-mobile excluir">
                                            Excluir
                                        </button>
                                    </form>

                                </div>

                            </article>
                        <?php endforeach; ?>

                    </div>

                <?php else: ?>

                    <div class="admin-vazio">
                        <span>🌱</span>
                        <h3>Nenhuma planta cadastrada</h3>
                        <p>Comece adicionando a primeira planta ao inventário.</p>

                        <a href="planta_form.php" class="admin-nova-planta">
                            ＋ Cadastrar primeira planta
                        </a>
                    </div>

                <?php endif; ?>

            </section>

            <footer class="dashboard-rodape">
                <span>Inventário Botânico · UFC</span>
                <span>Pesquisa e educação ambiental</span>
            </footer>

        </div>
    </main>

</div>

</body>
</html>
