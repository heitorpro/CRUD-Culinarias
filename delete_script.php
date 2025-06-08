<?php
include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Pega o ID da receita do formulário
    $idReceita = $_POST['idReceita'];

    // Validação básica: verifica se o ID é um número inteiro
    if (!filter_var($idReceita, FILTER_VALIDATE_INT)) {
        die("ID de receita inválido.");
    }

    // Prepara a consulta SQL para exclusão
    // ATENÇÃO: Deletando da tabela 'receitas'
    $sql = "DELETE FROM receitas WHERE idReceita = ?";
    $stmt = $conn->prepare($sql);

    // Verifica se a preparação da consulta falhou
    if ($stmt === false) {
        die("Erro na preparação da consulta: " . $conn->error);
    }

    // Vincula o parâmetro e executa a consulta
    // 'i' indica que o parâmetro é um integer
    $stmt->bind_param("i", $idReceita);

    if ($stmt->execute()) {
        echo "<script>alert('Receita excluída com sucesso!');</script>";
        echo "<script>window.location.href = 'receitas.php';</script>"; // Redireciona para a página de receitas
    } else {
        echo "<script>alert('Erro ao excluir receita: " . $stmt->error . "');</script>";
        echo "<script>window.location.href = 'index.php';</script>"; // Redireciona de volta para a página principal em caso de erro
    }

    $stmt->close();
} else {
    // Se o método não for POST, redireciona para a página principal ou exibe um erro
    header("Location: index.php");
    exit();
}

$conn->close(); // Fecha a conexão com o banco de dados
?>