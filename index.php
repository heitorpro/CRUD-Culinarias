<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gerenciador de Receitas Culinárias - Início</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .jumbotron {
            background-color: #e9ecef;
            padding: 4rem 2rem;
            margin-bottom: 2rem;
            border-radius: .3rem;
            text-align: center;
        }
        .feature-icon {
            font-size: 3rem;
            color: #28a745; /* Cor verde do sucesso */
        }
        .card-img-top {
            height: 200px; /* Altura fixa para as imagens */
            object-fit: cover; /* Garante que a imagem preencha o espaço sem distorcer */
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
        <div class="p-5 mb-4 bg-light rounded-3 text-center">
            <div class="container-fluid py-5">
                <h1 class="display-4 fw-bold text-success">Bem-vindo ao seu Gerenciador de Receitas!</h1>
                <p class="col-md-8 fs-4 mx-auto">Organize, encontre e compartilhe suas delícias culinárias de forma fácil e intuitiva.</p>
                <hr class="my-4">
                <p>Pronto para começar a catalogar seus pratos favoritos?</p>
                <div class="d-grid gap-2 d-md-flex justify-content-center">
                    <a href="cadastro.php" class="btn btn-success btn-lg px-4 me-md-2" role="button">
                        <i class="bi bi-journal-plus"></i> Cadastrar Nova Receita
                    </a>
                    <a href="receitas.php" class="btn btn-outline-info btn-lg px-4" role="button">
                        <i class="bi bi-search"></i> Pesquisar Receitas
                    </a>
                </div>
            </div>
        </div>

        <h2 class="text-center mb-4 text-secondary">Inspire-se na Culinária!</h2>
        <div class="row row-cols-1 row-cols-md-3 g-4 mb-5">
            <div class="col">
                <div class="card h-100">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcScGJ9qeqjbpfB9R8KeaPLo2cBxPLrUvL3e8A&s" class="card-img-top" alt="Prato Principal">
                    <div class="card-body">
                        <h5 class="card-title">Delícias do Almoço</h5>
                        <p class="card-text">Explore uma variedade de pratos principais que vão do simples ao sofisticado.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQpUFrC9k99qS3MqBOm0tEPk2ChxAXovY6qcw&s" class="card-img-top" alt="Sobremesas">
                    <div class="card-body">
                        <h5 class="card-title">Doces e Sobremesas</h5>
                        <p class="card-text">Receitas para adoçar a vida, desde bolos clássicos até mousses leves.</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card h-100">
                    <img src="https://talkbeer.com.br/wp-content/uploads/2024/12/Receitas-de-Lanches-Rapidos-para-o-Fim-de-Semana.jpg" class="card-img-top" alt="Lanches e Aperitivos">
                    <div class="card-body">
                        <h5 class="card-title">Lanches Rápidos e Aperitivos</h5>
                        <p class="card-text">Ideias criativas para seus lanches, petiscos e entradas saborosas.</p>
                    </div>
                </div>
            </div>
        </div>

        <footer class="text-center text-muted py-3">
            <p>&copy; 2025 Gerenciador de Receitas Culinárias. Todos os direitos reservados.</p>
        </footer>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>