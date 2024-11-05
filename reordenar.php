<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id'];
    $acao = $_POST['acao'];

    // Obter a ordem atual da tarefa
    $sql = "SELECT ordem FROM tarefas WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($ordem_atual);
    $stmt->fetch();
    $stmt->close();

    // Obter o ID e a ordem da tarefa que será trocada
    if ($acao === 'subir') {
        $sql = "SELECT id, ordem FROM tarefas WHERE ordem = ?";
        $nova_ordem = $ordem_atual - 1;
    } elseif ($acao === 'descer') {
        $sql = "SELECT id, ordem FROM tarefas WHERE ordem = ?";
        $nova_ordem = $ordem_atual + 1;
    }

    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $nova_ordem);
    $stmt->execute();
    $stmt->bind_result($id_troca, $ordem_troca);
    $stmt->fetch();
    $stmt->close();

    // Definir uma ordem temporária para evitar duplicação
    $sql = "UPDATE tarefas SET ordem = 999999 WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("i", $id_troca);
    $stmt->execute();
    $stmt->close();

    // Atualizar a ordem da tarefa original para a nova ordem
    $sql = "UPDATE tarefas SET ordem = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $nova_ordem, $id);
    $stmt->execute();
    $stmt->close();

    // Atualizar a tarefa que estava na nova ordem temporária para a ordem original
    $sql = "UPDATE tarefas SET ordem = ? WHERE id = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("ii", $ordem_atual, $id_troca);
    $stmt->execute();
    $stmt->close();

    // Redirecionar para a página inicial após a reordenação
    header("Location: index.php");
    exit();
}
?>


