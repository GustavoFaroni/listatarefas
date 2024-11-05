<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];

    // Obter a ordem da tarefa a ser excluída
    $sql = "SELECT ordem FROM tarefas WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($ordem);
    $stmt->fetch();
    $stmt->close();

    // Excluir a tarefa
    $sql = "DELETE FROM tarefas WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->close();

    // Atualizar as ordens das tarefas restantes
    $sql = "UPDATE tarefas SET ordem = ordem - 1 WHERE ordem > ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $ordem);
    $stmt->execute();
    $stmt->close();

    // Redirecionar para a página inicial após a exclusão
    header("Location: index.php");
    exit();
}
?>
