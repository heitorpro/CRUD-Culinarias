<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Receita - Smart Lab</title>
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
        .ingredient-group {
            border: 1px solid #dee2e6;
            padding: 15px;
            margin-bottom: 15px;
            border-radius: 5px;
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

    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-success text-white">
                        <h4 class="mb-0"><i class="bi bi-journal-plus"></i> Nova Receita</h4>
                    </div>
                    <div class="card-body">
                        <form action="cadastro_script.php" method="POST">
                            <div class="mb-3">
                                <label for="nome_receita" class="form-label">Nome da Receita <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="nome_receita" name="nome_receita" placeholder="Ex: Bolo de Chocolate" required>
                            </div>
                            <div class="mb-3">
                                <label for="categoria" class="form-label">Categoria <span class="text-danger">*</span></label>
                                <select class="form-select" id="categoria" name="categoria" required>
                                    <option value="" disabled selected>Selecione uma categoria</option>
                                    <option value="doce">Doce</option>
                                    <option value="salgado">Salgado</option>
                                    <option value="bebida">Bebida</option>
                                    <option value="entrada">Entrada</option>
                                    <option value="prato_principal">Prato Principal</option>
                                    <option value="sobremesa">Sobremesa</option>
                                    <option value="outro">Outro</option>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="tempo_preparo" class="form-label">Tempo de Preparo (minutos) <span class="text-danger">*</span></label>
                                    <input type="number" class="form-control" id="tempo_preparo" name="tempo_preparo" min="1" placeholder="Ex: 45" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="rendimento" class="form-label">Rendimento <span class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="rendimento" name="rendimento" placeholder="Ex: 8 porções" required>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label for="instrucoes_preparo" class="form-label">Instruções de Preparo <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="instrucoes_preparo" name="instrucoes_preparo" rows="5" placeholder="Passo a passo da receita..." required></textarea>
                            </div>

                            <h5 class="mt-4 mb-3"><i class="bi bi-basket"></i> Ingredientes</h5>
                            <div id="ingredientes_container">
                                <div class="ingredient-group">
                                    <div class="row">
                                        <div class="col-md-5 mb-3">
                                            <label for="ingrediente_nome_1" class="form-label">Nome <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="ingrediente_nome_1" name="ingredientes[0][nome]" placeholder="Ex: Farinha de trigo"  >
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ingrediente_quantidade_1" class="form-label">Quant. <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="ingrediente_quantidade_1" name="ingredientes[0][quantidade]" placeholder="Ex: 250" >
                                        </div>
                                        <div class="col-md-3 mb-3">
                                            <label for="ingrediente_unidade_1" class="form-label">Unidade <span class="text-danger">*</span></label>
                                            <input type="text" class="form-control" id="ingrediente_unidade_1" name="ingredientes[0][unidade]" placeholder="Ex: gramas" >
                                        </div>
                                        <div class="col-md-1 d-flex align-items-end mb-3">
                                            <button type="button" class="btn btn-danger remove-ingredient-btn" style="display:none;" title="Remover ingrediente"><i class="bi bi-x-circle"></i></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <button type="button" id="add_ingrediente_btn" class="btn btn-outline-primary mb-4"><i class="bi bi-plus-circle"></i> Adicionar Ingrediente</button>

                            <div class="d-grid gap-2">
                                <button type="submit" class="btn btn-success btn-lg"><i class="bi bi-save"></i> Salvar Receita</button>
                                <a href="receitas.php" class="btn btn-outline-secondary btn-lg"><i class="bi bi-arrow-left-circle"></i> Voltar para Lista</a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            let ingredientCount = 1; // Contador para os grupos de ingredientes
            const ingredientesContainer = document.getElementById('ingredientes_container');
            const addIngredienteBtn = document.getElementById('add_ingrediente_btn');

            // Função para atualizar a visibilidade do botão de remover
            function updateRemoveButtonsVisibility() {
                const removeButtons = document.querySelectorAll('.remove-ingredient-btn');
                if (removeButtons.length > 1) {
                    removeButtons.forEach(btn => btn.style.display = 'block');
                } else {
                    removeButtons.forEach(btn => btn.style.display = 'none');
                }
            }

            // Adiciona o primeiro botão de remover se já houver mais de um ingrediente (caso a página seja carregada com dados pré-existentes, por exemplo)
            updateRemoveButtonsVisibility();

            addIngredienteBtn.addEventListener('click', function() {
                ingredientCount++;
                const newIngredientGroup = document.createElement('div');
                newIngredientGroup.classList.add('ingredient-group');
                newIngredientGroup.innerHTML = `
                    <div class="row">
                        <div class="col-md-5 mb-3">
                            <label for="ingrediente_nome_${ingredientCount}" class="form-label">Nome <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ingrediente_nome_${ingredientCount}" name="ingredientes[${ingredientCount - 1}][nome]" placeholder="Ex: Açúcar">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="ingrediente_quantidade_${ingredientCount}" class="form-label">Quant. <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ingrediente_quantidade_${ingredientCount}" name="ingredientes[${ingredientCount - 1}][quantidade]" placeholder="Ex: 1 xícara">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label for="ingrediente_unidade_${ingredientCount}" class="form-label">Unidade <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="ingrediente_unidade_${ingredientCount}" name="ingredientes[${ingredientCount - 1}][unidade]" placeholder="Ex: xícaras">
                        </div>
                        <div class="col-md-1 d-flex align-items-end mb-3">
                            <button type="button" class="btn btn-danger remove-ingredient-btn" title="Remover ingrediente"><i class="bi bi-x-circle"></i></button>
                        </div>
                    </div>
                `;
                ingredientesContainer.appendChild(newIngredientGroup);
                updateRemoveButtonsVisibility();
            });

            ingredientesContainer.addEventListener('click', function(event) {
                if (event.target.closest('.remove-ingredient-btn')) {
                    if (document.querySelectorAll('.ingredient-group').length > 1) {
                        event.target.closest('.ingredient-group').remove();
                        updateRemoveButtonsVisibility();
                    }
                }
            });
        });
    </script>
</body>
</html>