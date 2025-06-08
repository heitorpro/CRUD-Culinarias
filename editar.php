<?php
include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

// Verifica se um ID foi passado via GET
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Prepara a consulta para buscar os dados da receita
    $sql = "SELECT idReceita, nome_receita, categoria, tempo_preparo_minutos, rendimento, instrucoes_preparo FROM receitas WHERE idReceita = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $receita = $result->fetch_assoc();
    } else {
        // Redireciona se a receita não for encontrada
        header("Location: index.php?erro=receita_nao_encontrada");
        exit();
    }
    $stmt->close();
} else {
    // Redireciona se o ID não for especificado
    header("Location: index.php?erro=id_nao_especificado");
    exit();
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Receita</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <style>
        /* Estilos customizados, se necessário, ou para centralizar o formulário */
        .container {
            max-width: 800px;
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
        <h2 class="mb-4">Editar Receita</h2>
        <form action="editar_script.php" method="POST">
            <input type="hidden" name="idReceita" value="<?php echo $receita['idReceita']; ?>">

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

            <div class="d-flex justify-content-between">
                <button type="submit" class="btn btn-success">Salvar Alterações</button>
                <a href="receitas.php" class="btn btn-secondary">Cancelar</a>
            </div>
        </form>
    </div>

    <footer class="bg-light text-center py-3 mt-5">
        <p>&copy; <?php echo date("Y"); ?> Gerenciador de Receitas. Todos os direitos reservados.</p>
    </footer>

    <script src="js/bootstrap.bundle.min.js"></script>
</body>
</html>

<?php
$conn->close(); // Fecha a conexão com o banco de dados
?>