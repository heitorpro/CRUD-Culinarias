<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lista de Receitas - Gerenciador de Receitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .recipe-card {
            margin-bottom: 25px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
        .recipe-card .card-header {
            background-color: #28a745; /* Verde do sucesso */
            color: white;
            display: flex;
            justify-content: space-between;
            align-items: center;
            cursor: pointer; /* Indica que o header é clicável */
        }
        .recipe-card .card-header h3 {
            margin-bottom: 0;
            flex-grow: 1; /* Permite que o título ocupe o espaço disponível */
        }
        .ingredient-list li {
            font-size: 0.9em;
            margin-bottom: 3px;
        }
        .action-buttons a,
        .action-buttons form {
            display: inline-block; /* Garante que botões e formulário fiquem na mesma linha */
            margin-left: 10px; /* Espaçamento entre os botões */
        }
        .action-buttons form {
            margin-bottom: 0; /* Remove margem inferior padrão de formulários */
        }
        /* Estilo para o ícone de expandir/contrair */
        .collapse-icon {
            transition: transform 0.3s ease;
        }
        .collapse-icon.collapsed {
            transform: rotate(-90deg); /* Ícone para baixo quando contraído */
        }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-success">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="bi bi-chef-hat"></i> Gerenciador de Receitas
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro.php">Cadastrar Receita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="receitas.php">Pesquisar Receitas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h1 class="mb-4 text-center text-success"><i class="bi bi-list-ul"></i> Todas as Receitas</h1>

        <form action="receitas.php" method="GET" class="mb-4">
            <div class="input-group">
                <input type="text" class="form-control" placeholder="Pesquisar receita por nome..." name="search_query" value="<?php echo htmlspecialchars($_GET['search_query'] ?? ''); ?>">
                <button class="btn btn-outline-success" type="submit"><i class="bi bi-search"></i> Pesquisar</button>
                <?php if (isset($_GET['search_query']) && !empty($_GET['search_query'])): ?>
                    <a href="receitas.php" class="btn btn-outline-secondary"><i class="bi bi-x-circle"></i> Limpar Pesquisa</a>
                <?php endif; ?>
            </div>
        </form>
        <?php
        // Inclui o arquivo de conexão com o banco de dados
        require('conexao.php');

        $search_query = '';
        $sql_receitas = "SELECT idReceita, nome_receita, categoria, tempo_preparo_minutos, rendimento, instrucoes_preparo FROM receitas";
        $params = [];
        $types = '';

        // Verifica se há um termo de pesquisa
        if (isset($_GET['search_query']) && !empty($_GET['search_query'])) {
            $search_query = '%' . $_GET['search_query'] . '%'; // Adiciona curingas para a pesquisa LIKE
            $sql_receitas .= " WHERE nome_receita LIKE ?"; // Adiciona a condição WHERE
            $params[] = $search_query;
            $types .= 's'; // 's' para string
        }

        $sql_receitas .= " ORDER BY nome_receita ASC"; // Mantém a ordenação

        // Prepara e executa a consulta
        $stmt_receitas = $conn->prepare($sql_receitas);

        if ($stmt_receitas === false) {
            echo '<div class="alert alert-danger text-center" role="alert">
                    <h4 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro Interno!</h4>
                    <p>Não foi possível preparar a consulta de receitas: ' . $conn->error . '</p>
                  </div>';
        } else {
            if (!empty($params)) {
                $stmt_receitas->bind_param($types, ...$params);
            }
            $stmt_receitas->execute();
            $result_receitas = $stmt_receitas->get_result();

            if ($result_receitas->num_rows > 0) {
                // Se houver receitas, exibe-as
                while ($receita = $result_receitas->fetch_assoc()) {
        ?>
                    <div class="card recipe-card">
                        <div class="card-header" id="heading<?php echo $receita['idReceita']; ?>">
                            <h3 class="card-title mb-0">
                                <button class="btn btn-link text-white text-decoration-none d-flex align-items-center w-100" type="button" data-bs-toggle="collapse" data-bs-target="#collapse<?php echo $receita['idReceita']; ?>" aria-expanded="false" aria-controls="collapse<?php echo $receita['idReceita']; ?>">
                                    <i class="bi bi-book me-2"></i> <?php echo htmlspecialchars($receita['nome_receita']); ?>
                                    <i class="bi bi-chevron-down ms-auto collapse-icon"></i> </button>
                            </h3>
                            <div class="action-buttons">
                                <a href="editar.php?id=<?php echo $receita['idReceita']; ?>" class="btn btn-secondary btn-sm" title="Editar Receita">
                                    <i class="bi bi-pencil-square"></i> Editar
                                </a>
                                <form action="delete_script.php" method="POST" class="d-inline" onsubmit="return confirm('Tem certeza que deseja excluir esta receita? Esta ação é irreversível.');">
                                    <input type="hidden" name="idReceita" value="<?php echo $receita['idReceita']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm" title="Excluir Receita">
                                        <i class="bi bi-trash"></i> Excluir
                                    </button>
                                </form>
                            </div>
                        </div>
                        
                        <div id="collapse<?php echo $receita['idReceita']; ?>" class="collapse" aria-labelledby="heading<?php echo $receita['idReceita']; ?>" data-bs-parent="#accordionRecipes">
                            <div class="card-body">
                                <p><strong>Categoria:</strong> <?php echo htmlspecialchars($receita['categoria']); ?></p>
                                <?php if (!empty($receita['tempo_preparo_minutos'])): ?>
                                    <p><strong>Tempo de Preparo:</strong> <?php echo htmlspecialchars($receita['tempo_preparo_minutos']); ?> minutos</p>
                                <?php endif; ?>
                                <?php if (!empty($receita['rendimento'])): ?>
                                    <p><strong>Rendimento:</strong> <?php echo htmlspecialchars($receita['rendimento']); ?></p>
                                <?php endif; ?>
                                
                                <h5 class="mt-4"><i class="bi bi-basket"></i> Ingredientes:</h5>
                                <ul class="list-group list-group-flush ingredient-list">
                                    <?php
                                    // Query para buscar os ingredientes desta receita
                                    $sql_ingredientes = "SELECT nome_ingrediente, quantidade, unidade_medida FROM ingredientes WHERE fk_idReceita = ? ORDER BY nome_ingrediente ASC";
                                    $stmt_ingredientes = $conn->prepare($sql_ingredientes);

                                    if ($stmt_ingredientes === false) {
                                        echo '<li class="list-group-item text-danger">Erro ao carregar ingredientes: ' . $conn->error . '</li>';
                                    } else {
                                        $stmt_ingredientes->bind_param("i", $receita['idReceita']);
                                        $stmt_ingredientes->execute();
                                        $result_ingredientes = $stmt_ingredientes->get_result();

                                        if ($result_ingredientes->num_rows > 0) {
                                            while ($ingrediente = $result_ingredientes->fetch_assoc()) {
                                                echo '<li class="list-group-item">';
                                                echo htmlspecialchars($ingrediente['nome_ingrediente']);
                                                if (!empty($ingrediente['quantidade'])) {
                                                    echo ' - ' . htmlspecialchars($ingrediente['quantidade']);
                                                }
                                                if (!empty($ingrediente['unidade_medida'])) {
                                                    echo ' ' . htmlspecialchars($ingrediente['unidade_medida']);
                                                }
                                                echo '</li>';
                                            }
                                        } else {
                                            echo '<li class="list-group-item text-muted">Nenhum ingrediente cadastrado para esta receita.</li>';
                                        }
                                        $stmt_ingredientes->close();
                                    }
                                    ?>
                                </ul>

                                <h5 class="mt-4"><i class="bi bi-card-text"></i> Instruções de Preparo:</h5>
                                <p class="card-text"><?php echo nl2br(htmlspecialchars($receita['instrucoes_preparo'])); ?></p>
                            </div>
                        </div>
                    </div>
        <?php
                } // Fim do while de receitas
            } else {
                // Se não houver receitas cadastradas OU não houver resultados para a pesquisa
                if (!empty($_GET['search_query'])) {
                    echo '<div class="alert alert-warning text-center" role="alert">
                                <h4 class="alert-heading"><i class="bi bi-info-circle"></i> Nenhuma Receita Encontrada!</h4>
                                <p>Não foram encontradas receitas com o termo "' . htmlspecialchars($_GET['search_query']) . '".</p>
                            </div>';
                } else {
                    echo '<div class="alert alert-info text-center" role="alert">
                                <h4 class="alert-heading"><i class="bi bi-info-circle"></i> Nenhuma Receita Cadastrada!</h4>
                                <p>Parece que ainda não há receitas cadastradas. Que tal adicionar uma?</p>
                            </div>';
                }
            }
            $stmt_receitas->close(); // Fecha a declaração da receita
        }
        // Fecha a conexão com o banco de dados
        $conn->close();
        ?>

        <div class="text-center mt-5 mb-5">
            <a href="index.php" class="btn btn-secondary btn-lg"><i class="bi bi-house"></i> Voltar para a Página Inicial</a>
            <a href="cadastro.php" class="btn btn-success btn-lg ms-3"><i class="bi bi-journal-plus"></i> Cadastrar Nova Receita</a>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Script para girar o ícone de chevron ao expandir/contrair
        document.querySelectorAll('.card-header button[data-bs-toggle="collapse"]').forEach(button => {
            button.addEventListener('click', function() {
                const icon = this.querySelector('.collapse-icon');
                if (icon) {
                    icon.classList.toggle('collapsed');
                }
            });
        });
    </script>
</body>
</html>