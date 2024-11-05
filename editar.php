<?php require_once 'config.php'; 
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $id = $_POST['id']; 
    $nome = $_POST['nome']; 
    $valor = $_POST['valor']; 
    $prazo = $_POST['prazo']; // Verifica se o novo nome da tarefa já existe, exceto para a tarefa atual 
    $sql = "SELECT COUNT(*) FROM tarefas WHERE nome = ? AND id != ?";
    $stmt = $conexao->prepare($sql); 
    $stmt->bind_param("si", $nome, $id);
    $stmt->execute(); $stmt->bind_result($count); 
    $stmt->fetch(); $stmt->close(); 
    if ($count > 0) { 
        echo "<script>alert('O nome da tarefa já existe!'); window.location.href = 'index.php';</script>"; 
    } else { // Atualiza a tarefa 
    $sql = "UPDATE tarefas SET nome = ?, custo = ?, prazo = ? WHERE id = ?"; 
    $stmt = $conexao->prepare($sql); 
    $stmt->bind_param("sdsi", $nome, $valor, $prazo, $id); 
    if ($stmt->execute()) { 
        echo "Tarefa atualizada com sucesso!"; // Redireciona de volta para a página inicial 
        header("Location: index.php"); 
    exit(); 
    } else { echo "Erro ao atualizar tarefa.";
    } $stmt->close(); 
} 

} 

?>