<?php
require_once 'config.php';

    $sql = "SELECT * FROM tarefas ORDER BY ordem ASC";
    $result = $conexao->query($sql);

    $first_row = $result->fetch_assoc(); 
    $first_id = $first_row["id"];
    $result->data_seek(0); // Reiniciar ponteiro do result set
    $last_row = $result->fetch_assoc(); 
    $result->data_seek($result->num_rows -1);
    $last_id = $result->fetch_assoc()["id"]; 
    $result->data_seek(0); // Reiniciar ponteiro do result set



    // Verifica se o novo nome da tarefa já existe 
    $sql = "SELECT COUNT(*) FROM tarefas WHERE nome = ? AND id != ?"; 
    $stmt = $conexao->prepare($sql); 
    $stmt->bind_param("si", $nome, $id); 
    $stmt->execute(); 
    $stmt->bind_result($count); 
    $stmt->fetch(); 
    $stmt->close(); 
    if ($count > 0) { echo "O nome da tarefa já existe!"; 
    }else { // Atualiza a tarefa 
        $sql = "UPDATE tarefas SET nome = ?, custo = ?, prazo = ? WHERE id = ?"; 
        $stmt = $conexao->prepare($sql); 
        $stmt->bind_param("sdsi", $nome, $custo, $data_limite, $id);
    }
    $stmt->close();


?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Tarefa</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="css/style.css">
    <style>
    .caixal {
        border: 1px solid black;
        padding: 15px;
        border-radius: 8px;
        width: 350px;
        background-color: #45a049;
        margin: 20px;
        display: flex;
        justify-content: space-between;
    }

    .caixal.destaque {
        background-color: #ffeb3b;
    }

    .bot {
        margin-top: 20px;
        margin-bottom: 30px;
    }

    .st {
        margin: 20px;
    }

    .centralizar {
        margin-top: 5px;
    }

    .bote {
        color: white;
        background-color: black;
        border-radius: 15px;
        transition: 0.5s;
        padding: 15px;
        display: inline-flex;
        align-items: center;
        border: none ;
    }

    .botl {
        
        margin-top: 20px;
        color: white;
        background-color: black;
        border-radius: 15px;
        transition: 0.5s;
        padding: 15px;
        height: 18px;
        display: inline-flex;
        align-items: center;
        border: none;
    }

    .bote:hover {
        transition: 0.5s;
        background-color: #197a22;
    }

    .botl:hover {
        transition: 0.5s;
        background-color: darkgray;
    }

    .setas {
        display: flex;
        flex-direction: column;
        justify-content: space-around;
    }

    .exibi {
        flex-grow: 1;
    }
    .cima{
        align-items: center;
        background: black;
        border-radius: 36px;
        color: #fff;
        display: inline-flex;
        font-size: 30px;
        height: 45px;
        justify-content: center;
        margin: 5px;
        width: 45px;
        padding-bottom: 4px;
        border: none;
    }
    .baixo{
        align-items: center;
        background: black;
        border-radius: 36px;
        color: #fff;
        display: inline-flex;
        font-size: 30px; 
        height: 45px;
        justify-content: center;
        margin: 5px;
        width: 45px;
        padding-bottom: 4px;
        border: none;
    }
    .cima:hover {
        transition: 0.5s;
        background-color: darkgray;
    }
    .baixo:hover {
        transition: 0.5s;
        background-color: darkgray;
    }

    </style>
    

</head>

<body>
    <header>
        <div>
            <h1><I>Lista de tarefas</I></h1>
        </div>
    </header>
    <main>
        <div class="centro">
            <?php if(isset($result) && $result->num_rows >0): ?>
            <?php while($row = $result-> fetch_assoc()) : ?>
            <div class="caixal  <?php echo ($row['custo'] >= 1000) ? 'destaque' : ''; ?>">
                <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                <!--parte exibida na lista-->
                <div class="exibi">
                    <strong>nome: </strong> <?php echo $row["nome"]; ?><br>
                    <strong>custo: </strong>R$ <?php echo $row["custo"]; ?><br>
                    <strong>data limite: </strong> <?php echo date("d/m/Y", strtotime($row["prazo"])); ?><br>
                    <!--botao editar-->
                    <button class="botl"
                        onclick="abrirPopup('<?php echo $row['id']; ?>', '<?php echo $row['nome']; ?>', '<?php echo $row['custo']; ?>', '<?php echo $row['prazo']; ?>')"><i class="fa fa-wrench" style="font-size:20px" aria-hidden="true"></i></button>
                    <!--botao excluir-->
                    <form action="excluir.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <button class="botl" type="submit"
                            onclick="return confirm('Tem certeza de que deseja excluir esta tarefa?')">
                            <i style="font-size:20px" class="fa">&#xf014;</i>
                        </button>
                    </form>
                </div>
                <!--setas para reordenar a lista-->
                <div class="setas">
                    <?php if ($row['id'] != $first_id): ?>
                    <form action="reordenar.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="acao" value="subir"> <button class="cima" type="submit">&#8593</button>
                    </form>
                    <?php endif; ?>
                    <?php if ($row['id'] != $last_id): ?>
                    <form action="reordenar.php" method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                        <input type="hidden" name="acao" value="descer">
                        <button class="baixo" type="submit">&#8595</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
            <?php endwhile; ?>
            <?php else : ?>
            <h1 class="st">sem tarefas</h1>
            <?php endif; ?>
            <!--botao adicionar tarefa-->
            <div class="bot"><a class="botao" href="addTarefa.html">adicionar Tarefa</a></div>
        </div>
        <!-- pop up do editar -->
        <div id="popup" style="display: none;">
            <div class="form-container">
                <form action="editar.php" method="POST">
                    <input type="hidden" id="edit-id" name="id">
                    <div class="form-group">
                        <label for="edit-nome">Nome da Tarefa</label>
                        <input type="text" id="edit-nome" name="nome" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-valor">Valor (R$)</label>
                        <input type="number" id="edit-valor" name="valor" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="edit-prazo">Data Limite</label>
                        <input type="date" id="edit-prazo" name="prazo" required>
                    </div>
                    <div class="centralizar">
                        <button class="bote" style="margin-right: 30px;" type="submit">Salvar</button>
                        <button class="bote" type="button" onclick="fecharPopup()">Cancelar</button>
                    </div>
                </form>
            </div>
        </div>
    </main>


    <script>
    function abrirPopup(id, nome, valor, prazo) {
        document.getElementById('edit-id').value = id;
        document.getElementById('edit-nome').value = nome;
        document.getElementById('edit-valor').value = valor;
        document.getElementById('edit-prazo').value = prazo;
        document.getElementById('popup').style.display = 'block';
    }

    function fecharPopup() {
        document.getElementById('popup').style.display = 'none';
    }
    </script>



</body>

</html>