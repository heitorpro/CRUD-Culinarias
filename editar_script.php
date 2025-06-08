<?php
// ... (código PHP existente de editar_script.php) ...
include 'conexao.php'; // Inclui o arquivo de conexão com o banco de dados

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // 1. Pega os dados da receita do formulário
    $idReceita = $_POST['idReceita'];
    $nome_receita = $_POST['nome_receita'];
    $categoria = $_POST['categoria'];
    $tempo_preparo_minutos = $_POST['tempo_preparo_minutos'];
    $rendimento = $_POST['rendimento'];
    $instrucoes_preparo = $_POST['instrucoes_preparo'];

    // Validação básica dos dados da receita
    if (empty($nome_receita) || empty($categoria) || empty($instrucoes_preparo)) {
        die("Nome da receita, categoria e instruções de preparo são obrigatórios.");
    }

    // Inicia uma transação para garantir a atomicidade das operações (receita e ingredientes)
    $conn->begin_transaction();

    try {
        // 2. Atualiza a receita principal
        $sqlReceita = "UPDATE receitas SET nome_receita = ?, categoria = ?, tempo_preparo_minutos = ?, rendimento = ?, instrucoes_preparo = ? WHERE idReceita = ?";
        $stmtReceita = $conn->prepare($sqlReceita);
        if ($stmtReceita === false) {
            throw new Exception("Erro na preparação da consulta da receita: " . $conn->error);
        }
        $stmtReceita->bind_param("ssissi", $nome_receita, $categoria, $tempo_preparo_minutos, $rendimento, $instrucoes_preparo, $idReceita);
        $stmtReceita->execute();
        $stmtReceita->close();

        // 3. Processa os ingredientes
        $ingredientes = $_POST['ingredientes'] ?? [];
        $ingredientesRemovidos = $_POST['ingredientes_removidos'] ?? [];

        // Excluir ingredientes marcados para remoção
        if (!empty($ingredientesRemovidos)) {
            // Cria placeholders (?) para cada ID a ser removido
            $placeholders = implode(',', array_fill(0, count($ingredientesRemovidos), '?'));
            $sqlDeleteIngredientes = "DELETE FROM ingredientes WHERE idIngrediente IN ($placeholders)";
            $stmtDeleteIngredientes = $conn->prepare($sqlDeleteIngredientes);
            if ($stmtDeleteIngredientes === false) {
                throw new Exception("Erro na preparação da exclusão de ingredientes: " . $conn->error);
            }
            // 'i' repetido para cada inteiro
            $types = str_repeat('i', count($ingredientesRemovidos));
            $stmtDeleteIngredientes->bind_param($types, ...$ingredientesRemovidos);
            $stmtDeleteIngredientes->execute();
            $stmtDeleteIngredientes->close();
        }

        // Inserir ou atualizar ingredientes
        foreach ($ingredientes as $ingrediente) {
            $idIngrediente = $ingrediente['idIngrediente'];
            $nome_ingrediente = $ingrediente['nome_ingrediente'];
            $quantidade = $ingrediente['quantidade'];
            $unidade_medida = $ingrediente['unidade_medida'];

            if (empty($nome_ingrediente)) {
                // Pular ingredientes sem nome (se forem campos vazios adicionados via JS e não preenchidos)
                continue;
            }

            if ($idIngrediente == 0) { // Novo ingrediente (ID 0)
                $sqlInsertIngrediente = "INSERT INTO ingredientes (nome_ingrediente, quantidade, unidade_medida, fk_idReceita) VALUES (?, ?, ?, ?)";
                $stmtInsertIngrediente = $conn->prepare($sqlInsertIngrediente);
                if ($stmtInsertIngrediente === false) {
                    throw new Exception("Erro na preparação da inserção de ingrediente: " . $conn->error);
                }
                $stmtInsertIngrediente->bind_param("sssi", $nome_ingrediente, $quantidade, $unidade_medida, $idReceita);
                $stmtInsertIngrediente->execute();
                $stmtInsertIngrediente->close();
            } else { // Ingrediente existente (ID > 0)
                $sqlUpdateIngrediente = "UPDATE ingredientes SET nome_ingrediente = ?, quantidade = ?, unidade_medida = ? WHERE idIngrediente = ? AND fk_idReceita = ?";
                $stmtUpdateIngrediente = $conn->prepare($sqlUpdateIngrediente);
                if ($stmtUpdateIngrediente === false) {
                    throw new Exception("Erro na preparação da atualização de ingrediente: " . $conn->error);
                }
                $stmtUpdateIngrediente->bind_param("sssii", $nome_ingrediente, $quantidade, $unidade_medida, $idIngrediente, $idReceita);
                $stmtUpdateIngrediente->execute();
                $stmtUpdateIngrediente->close();
            }
        }

        $conn->commit(); // Confirma todas as operações se tudo correu bem
        echo "<script>alert('Receita e ingredientes atualizados com sucesso!');</script>";
        echo "<script>window.location.href = 'receitas.php';</script>"; // ALTERADO: Redireciona para receitas.php

    } catch (Exception $e) {
        $conn->rollback(); // Reverte todas as operações em caso de erro
        echo "<script>alert('Erro ao atualizar receita e ingredientes: " . $e->getMessage() . "');</script>";
        echo "<script>window.location.href = 'receitas.php?id=" . $idReceita . "';</script>"; // ALTERADO: Redireciona para receitas.php em caso de erro
    }

} else {
    // Se o método não for POST, redireciona para a página principal ou exibe um erro
    header("Location: receitas.php"); // ALTERADO: Redireciona para receitas.php
    exit();
}

$conn->close(); // Fecha a conexão com o banco de dados
?>