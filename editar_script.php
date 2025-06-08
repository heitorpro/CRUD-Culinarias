<?php
include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega os dados do formulário
    // ATENÇÃO: Os nomes dos campos agora são da tabela 'receitas'
    $idReceita = $_POST['idReceita'];
    $nome_receita = $_POST['nome_receita'];
    $categoria = $_POST['categoria'];
    $tempo_preparo_minutos = $_POST['tempo_preparo_minutos'];
    $rendimento = $_POST['rendimento'];
    $instrucoes_preparo = $_POST['instrucoes_preparo'];

    // Validação básica dos dados
    if (empty($nome_receita) || empty($categoria) || empty($instrucoes_preparo)) {
        die("Nome da receita, categoria e instruções de preparo são obrigatórios.");
    }

    // Prepara a consulta SQL para atualização
    // ATENÇÃO: A tabela e as colunas agora são da tabela 'receitas'
    $sql = "UPDATE receitas SET nome_receita = ?, categoria = ?, tempo_preparo_minutos = ?, rendimento = ?, instrucoes_preparo = ? WHERE idReceita = ?";
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação da consulta falhou
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Vincula os parâmetros e executa a consulta
    // 'ssisii' indica que os parâmetros são string, string, integer, string, string, integer
    // Ajuste o tipo de 'tempo_preparo_minutos' e 'idReceita' para 'i' (integer)
    $stmt->bind_param("ssissi", $nome_receita, $categoria, $tempo_preparo_minutos, $rendimento, $instrucoes_preparo, $idReceita);

    if ($stmt->execute()) {
        echo "<script>alert('Receita atualizada com sucesso!');</script>";
        echo "<script>window.location.href = 'index.php';</script>"; // Redireciona para a página principal
    } else {
        echo "<script>alert('Erro ao atualizar receita: " . $stmt->error . "');</script>";
        echo "<script>window.location.href = 'editar.php?id=" . $idReceita . "';</script>"; // Redireciona de volta para a página de edição em caso de erro
    }

    $stmt->close();
} else {
    // Se o método não for POST, redireciona para a página principal ou exibe um erro
    header("Location: index.php");
    exit();
}

$conn->close(); // Fecha a conexão com o banco de dados
?>