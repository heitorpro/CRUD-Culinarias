<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Resultado do Cadastro - Gerenciador de Receitas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .card {
            margin-top: 50px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-secondary text-white">
                        <h4 class="mb-0"><i class="bi bi-info-circle"></i> Status do Cadastro</h4>
                    </div>
                    <div class="card-body text-center">
                        <?php
                        // Inclui o arquivo de conexão com o banco de dados
                        require('conexao.php');

                        // Verifica se o formulário foi enviado via POST
                        if ($_SERVER["REQUEST_METHOD"] == "POST") {

                            // 1. Receber os Dados do Formulário (Receita)
                            $nome_receita = $_POST['nome_receita'] ?? '';
                            $categoria = $_POST['categoria'] ?? '';
                            $tempo_preparo_minutos = $_POST['tempo_preparo'] ?? NULL;
                            $rendimento = $_POST['rendimento'] ?? '';
                            $instrucoes_preparo = $_POST['instrucoes_preparo'] ?? '';
                            $ingredientes_post = $_POST['ingredientes'] ?? []; // Array de ingredientes

                            // Validação básica: verifica se campos obrigatórios não estão vazios
                            if (empty($nome_receita) || empty($categoria) || empty($instrucoes_preparo)) {
                                echo '<div class="alert alert-danger" role="alert">
                                        <h4 class="alert-heading"><i class="bi bi-exclamation-triangle"></i> Erro no Cadastro!</h4>
                                        <p>Por favor, preencha todos os campos obrigatórios para a receita.</p>
                                    </div>';
                            } else {
                                // 2. Inserir a Receita Principal na Tabela 'receitas'
                                // Usando prepared statements para segurança
                                $sql_receita = "INSERT INTO receitas (nome_receita, categoria, tempo_preparo_minutos, rendimento, instrucoes_preparo) VALUES (?, ?, ?, ?, ?)";
                                $stmt_receita = $conn->prepare($sql_receita);

                                if ($stmt_receita === false) {
                                    echo '<div class="alert alert-danger" role="alert">
                                            <h4 class="alert-heading"><i class="bi bi-x-circle"></i> Erro Interno!</h4>
                                            <p>Não foi possível preparar a inserção da receita: ' . $conn->error . '</p>
                                        </div>';
                                } else {
                                    // Ajusta tempo_preparo para NULL se for vazio
                                    if (empty($tempo_preparo_minutos)) {
                                        $tempo_preparo_minutos = NULL;
                                    }

                                    $stmt_receita->bind_param("ssiss", $nome_receita, $categoria, $tempo_preparo_minutos, $rendimento, $instrucoes_preparo);

                                    if ($stmt_receita->execute()) {
                                        $idReceita = $conn->insert_id; // Obtém o ID da receita recém-inserida
                                        $sucesso_total = true; // Flag para o sucesso geral

                                        // 3. Inserir os Ingredientes na Tabela 'ingredientes'
                                        if (!empty($ingredientes_post) && is_array($ingredientes_post)) {
                                            $sql_ingrediente = "INSERT INTO ingredientes (nome_ingrediente, quantidade, unidade_medida, fk_idReceita) VALUES (?, ?, ?, ?)";
                                            $stmt_ingrediente = $conn->prepare($sql_ingrediente);

                                            if ($stmt_ingrediente === false) {
                                                echo '<div class="alert alert-warning" role="alert">
                                                        <p>Receita cadastrada, mas houve um problema na preparação dos ingredientes: ' . $conn->error . '</p>
                                                    </div>';
                                                $sucesso_total = false; // A receita foi, mas ingredientes podem ter problemas
                                            } else {
                                                foreach ($ingredientes_post as $ingrediente) {
                                                    $nome_ingrediente = $ingrediente['nome'] ?? '';
                                                    $quantidade = $ingrediente['quantidade'] ?? '';
                                                    $unidade_medida = $ingrediente['unidade'] ?? '';

                                                    // Insere apenas se o nome do ingrediente não estiver vazio
                                                    if (!empty($nome_ingrediente)) {
                                                        $stmt_ingrediente->bind_param("sssi", $nome_ingrediente, $quantidade, $unidade_medida, $idReceita);
                                                        if (!$stmt_ingrediente->execute()) {
                                                            echo '<div class="alert alert-warning" role="alert">
                                                                    <p>Erro ao inserir ingrediente "' . htmlspecialchars($nome_ingrediente) . '": ' . $stmt_ingrediente->error . '</p>
                                                                </div>';
                                                            $sucesso_total = false; // Sinaliza que nem todos os ingredientes foram
                                                        }
                                                    }
                                                }
                                                $stmt_ingrediente->close();
                                            }
                                        }

                                        if ($sucesso_total) {
                                            echo '<div class="alert alert-success" role="alert">
                                                    <h4 class="alert-heading"><i class="bi bi-check-circle"></i> Sucesso!</h4>
                                                    <p>Receita "' . htmlspecialchars($nome_receita) . '" e seus ingredientes foram cadastrados com sucesso!</p>
                                                </div>';
                                        } else {
                                            echo '<div class="alert alert-info" role="alert">
                                                    <h4 class="alert-heading"><i class="bi bi-info-circle"></i> Cadastro Parcial!</h4>
                                                    <p>Receita "' . htmlspecialchars($nome_receita) . '" cadastrada, mas houve problemas com alguns ingredientes.</p>
                                                </div>';
                                        }

                                    } else {
                                        // Erro ao inserir a receita principal
                                        echo '<div class="alert alert-danger" role="alert">
                                                <h4 class="alert-heading"><i class="bi bi-x-circle"></i> Erro no Cadastro!</h4>
                                                <p>Erro ao cadastrar a receita: ' . $stmt_receita->error . '</p>
                                            </div>';
                                    }
                                }
                                $stmt_receita->close();
                            }
                        } else {
                            // Se a página for acessada diretamente sem POST
                            echo '<div class="alert alert-info" role="alert">
                                    <h4 class="alert-heading"><i class="bi bi-question-circle"></i> Acesso Inválido</h4>
                                    <p>Esta página deve ser acessada via formulário de cadastro de receitas.</p>
                                </div>';
                        }

                        // Fecha a conexão com o banco de dados
                        $conn->close();
                        ?>
                        <div class="mt-4">
                            <a href="cadastro.php" class="btn btn-primary btn-lg"><i class="bi bi-arrow-left-circle"></i> Voltar para o Cadastro</a>
                            <a href="receitas.php" class="btn btn-outline-info btn-lg ms-2"><i class="bi bi-list-ul"></i> Ver Receitas</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>