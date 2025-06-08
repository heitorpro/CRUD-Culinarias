<?php
// ... (código PHP existente de editar.php) ...
include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se um ID foi passado via GET
if (isset($_GET['id'])) {
    $idReceita = $_GET['id'];

    // 1. Prepara a consulta para buscar os dados da receita
    $sqlReceita = "SELECT idReceita, nome_receita, categoria, tempo_preparo_minutos, rendimento, instrucoes_preparo FROM receitas WHERE idReceita = ?";
    $stmtReceita = $conn->prepare($sqlReceita);
    $stmtReceita->bind_param("i", $idReceita);
    $stmtReceita->execute();
    $resultReceita = $stmtReceita->get_result();

    if ($resultReceita->num_rows > 0) {
        $receita = $resultReceita->fetch_assoc();
    } else {
        // Redireciona se a receita não for encontrada
        header("Location: receitas.php?erro=receita_nao_encontrada"); // Alterado para receitas.php
        exit();
    }
    $stmtReceita->close();

    // 2. Prepara a consulta para buscar os ingredientes da receita
    $sqlIngredientes = "SELECT idIngrediente, nome_ingrediente, quantidade, unidade_medida FROM ingredientes WHERE fk_idReceita = ?";
    $stmtIngredientes = $conn->prepare($sqlIngredientes);
    $stmtIngredientes->bind_param("i", $idReceita); // Corrigido $idReceeta para $idReceita
    $stmtIngredientes->execute();
    $resultIngredientes = $stmtIngredientes->get_result();

    $ingredientes = [];
    while ($row = $resultIngredientes->fetch_assoc()) {
        $ingredientes[] = $row;
    }
    $stmtIngredientes->close();

} else {
    // Redireciona se o ID não for especificado
    header("Location: receitas.php?erro=id_nao_especificado"); // Alterado para receitas.php
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Receita e Ingredientes</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        .container {
            max-width: 800px;
        }
        .ingrediente-item {
            display: flex;
            align-items: flex-end;
            margin-bottom: 15px;
            gap: 10px; /* Espaço entre os campos do ingrediente */
            border: 1px solid #dee2e6;
            padding: 10px;
            border-radius: .25rem;
        }
        .ingrediente-item .form-group {
            flex: 1; /* Faz com que os campos de texto preencham o espaço */
        }
        .ingrediente-item .form-group:last-child {
             flex: 0 0 auto; /* Botão de remover não ocupa espaço total */
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
                        <a class="nav-link active" aria-current="page" href="index.php">Início</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="cadastro.php">Cadastrar Receita</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="receitas.php">Pesquisar Receitas</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container mt-5">
        <h2 class="mb-4">Editar Receita e Ingredientes</h2>
        <form action="editar_script.php" method="POST">
            <input type="hidden" name="idReceita" value="<?php echo $receita['idReceita']; ?>">

            <h3>Detalhes da Receita</h3>
            <div class="mb-3">
                <label for="nome_receita" class="form-label">Nome da Receita:</label>
                <input type="text" class="form-control" id="nome_receita" name="nome_receita" value="<?php echo htmlspecialchars($receita['nome_receita']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="categoria" class="form-label">Categoria:</label>
                <input type="text" class="form-control" id="categoria" name="categoria" value="<?php echo htmlspecialchars($receita['categoria']); ?>" required>
            </div>

            <div class="mb-3">
                <label for="tempo_preparo_minutos" class="form-label">Tempo de Preparo (minutos):</label>
                <input type="number" class="form-control" id="tempo_preparo_minutos" name="tempo_preparo_minutos" value="<?php echo htmlspecialchars($receita['tempo_preparo_minutos']); ?>">
            </div>

            <div class="mb-3">
                <label for="rendimento" class="form-label">Rendimento:</label>
                <input type="text" class="form-control" id="rendimento" name="rendimento" value="<?php echo htmlspecialchars($receita['rendimento']); ?>">
            </div>

            <div class="mb-3">
                <label for="instrucoes_preparo" class="form-label">Instruções de Preparo:</label>
                <textarea class="form-control" id="instrucoes_preparo" name="instrucoes_preparo" rows="5" required><?php echo htmlspecialchars($receita['instrucoes_preparo']); ?></textarea>
            </div>

            <h3 class="mt-5 mb-3">Ingredientes</h3>
            <div id="ingredientes-container">
                <?php if (!empty($ingredientes)): ?>
                    <?php foreach ($ingredientes as $index => $ingrediente): ?>
                        <div class="ingrediente-item" data-id="<?php echo $ingrediente['idIngrediente']; ?>">
                            <input type="hidden" name="ingredientes[<?php echo $index; ?>][idIngrediente]" value="<?php echo $ingrediente['idIngrediente']; ?>">
                            <div class="form-group flex-grow-1">
                                <label for="nome_ingrediente_<?php echo $index; ?>" class="form-label visually-hidden">Nome do Ingrediente</label>
                                <input type="text" class="form-control" id="nome_ingrediente_<?php echo $index; ?>" name="ingredientes[<?php echo $index; ?>][nome_ingrediente]" placeholder="Nome do Ingrediente" value="<?php echo htmlspecialchars($ingrediente['nome_ingrediente']); ?>" required>
                            </div>
                            <div class="form-group flex-grow-1">
                                <label for="quantidade_<?php echo $index; ?>" class="form-label visually-hidden">Quantidade</label>
                                <input type="text" class="form-control" id="quantidade_<?php echo $index; ?>" name="ingredientes[<?php echo $index; ?>][quantidade]" placeholder="Quantidade (ex: 200)" value="<?php echo htmlspecialchars($ingrediente['quantidade']); ?>">
                            </div>
                            <div class="form-group flex-grow-1">
                                <label for="unidade_medida_<?php echo $index; ?>" class="form-label visually-hidden">Unidade de Medida</label>
                                <input type="text" class="form-control" id="unidade_medida_<?php echo $index; ?>" name="ingredientes[<?php echo $index; ?>][unidade_medida]" placeholder="Unidade (ex: g, ml, xícaras)" value="<?php echo htmlspecialchars($ingrediente['unidade_medida']); ?>">
                            </div>
                            <div class="form-group">
                                <button type="button" class="btn btn-danger remove-ingrediente">Remover</button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>
            <button type="button" class="btn btn-success mt-3" id="add-ingrediente">Adicionar Ingrediente</button>


            <div class="d-flex justify-content-between mt-5">
                <button type="submit" class="btn btn-primary">Salvar Alterações</button>
                <a href="receitas.php" class="btn btn-secondary">Cancelar</a> </div>
        </form>
    </div>

    <footer class="bg-light text-center py-3 mt-5">
        <p>&copy; <?php echo date("Y"); ?> Gerenciador de Receitas. Todos os direitos reservados.</p>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let ingredienteIndex = <?php echo count($ingredientes); ?>; // Começa após os ingredientes existentes

            document.getElementById('add-ingrediente').addEventListener('click', function() {
                const container = document.getElementById('ingredientes-container');
                const newItem = document.createElement('div');
                newItem.classList.add('ingrediente-item');
                newItem.innerHTML = `
                    <input type="hidden" name="ingredientes[${ingredienteIndex}][idIngrediente]" value="0"> <div class="form-group flex-grow-1">
                        <label for="nome_ingrediente_${ingredienteIndex}" class="form-label visually-hidden">Nome do Ingrediente</label>
                        <input type="text" class="form-control" id="nome_ingrediente_${ingredienteIndex}" name="ingredientes[${ingredienteIndex}][nome_ingrediente]" placeholder="Nome do Ingrediente" required>
                    </div>
                    <div class="form-group flex-grow-1">
                        <label for="quantidade_${ingredienteIndex}" class="form-label visually-hidden">Quantidade</label>
                        <input type="text" class="form-control" id="quantidade_${ingredienteIndex}" name="ingredientes[${ingredienteIndex}][quantidade]" placeholder="Quantidade (ex: 200)">
                    </div>
                    <div class="form-group flex-grow-1">
                        <label for="unidade_medida_${ingredienteIndex}" class="form-label visually-hidden">Unidade de Medida</label>
                        <input type="text" class="form-control" id="unidade_medida_${ingredienteIndex}" name="ingredientes[${ingredienteIndex}][unidade_medida]" placeholder="Unidade (ex: g, ml, xícaras)">
                    </div>
                    <div class="form-group">
                        <button type="button" class="btn btn-danger remove-ingrediente">Remover</button>
                    </div>
                `;
                container.appendChild(newItem);
                ingredienteIndex++;
                attachRemoveListeners();
            });

            function attachRemoveListeners() {
                document.querySelectorAll('.remove-ingrediente').forEach(button => {
                    button.onclick = function() {
                        const item = this.closest('.ingrediente-item');
                        // Se o ingrediente já existe no DB (tem um ID > 0), marca para exclusão
                        const idIngrediente = item.querySelector('input[name*="[idIngrediente]"]').value;
                        if (idIngrediente > 0) {
                            // Adiciona um campo hidden para marcar este ingrediente como "removido"
                            const hiddenRemovedInput = document.createElement('input');
                            hiddenRemovedInput.type = 'hidden';
                            hiddenRemovedInput.name = 'ingredientes_removidos[]';
                            hiddenRemovedInput.value = idIngrediente;
                            document.querySelector('form').appendChild(hiddenRemovedInput);
                        }
                        item.remove(); // Remove o elemento visualmente
                    };
                });
            }

            attachRemoveListeners(); // Anexa listeners aos botões de remover existentes
        });
    </script>
</body>
</html>

<?php
$conn->close(); // Fecha a conexão com o banco de dados
?>