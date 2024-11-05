<?php
require_once 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $nome = $_POST['nome'];
    $valor = $_POST['valor'];
    $prazo = $_POST['prazo'];

    // Verificar se o nome da tarefa já existe
    $sql = "SELECT COUNT(*) FROM tarefas WHERE nome = ?";
    $stmt = $conexao->prepare($sql);
    $stmt->bind_param("s", $nome);
    $stmt->execute();
    $stmt->bind_result($count);
    $stmt->fetch();
    $stmt->close();

    if ($count > 0) {
        echo "<script>alert('O nome da tarefa já existe!'); window.location.href = 'addTarefa.html';</script>";
    } else {
        // Encontrar a menor ordem disponível
        $sql = "SELECT MIN(ordem) AS min_ordem FROM (
                    SELECT ordem + 1 AS ordem FROM tarefas
                    UNION
                    SELECT 1
                    EXCEPT
                    SELECT ordem FROM tarefas
                ) AS sub";
        $result = $conexao->query($sql);
        $row = $result->fetch_assoc();
        $ordem = $row['min_ordem'];

        // Inserir a nova tarefa no banco de dados
        $sql = "INSERT INTO tarefas (nome, custo, prazo, ordem) VALUES (?, ?, ?, ?)";
        $stmt = $conexao->prepare($sql);
        $stmt->bind_param("sdsi", $nome, $valor, $prazo, $ordem);

        if ($stmt->execute()) {
            // Redirecionar para a página inicial após a inserção bem-sucedida
            header("Location: index.php");
            exit();
        } else {
            echo "Erro ao adicionar tarefa.";
        }
        $stmt->close();
    }
}
?>
