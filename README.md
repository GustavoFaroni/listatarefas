# Controle-tarefas
# Sistema de Lista de Tarefas

Este é um sistema web simples para gerenciar uma lista de tarefas. A aplicação permite que os usuários adicionem, editem, excluam e reordenem tarefas. 

## Pré-requisitos

1. **XAMPP**: Baixe e instale o XAMPP a partir de [aqui](https://www.apachefriends.org/index.html).
2. **Git**: Baixe e instale o Git a partir de [aqui](https://git-scm.com/).
3. **Navegador Web**: Google Chrome, Firefox, etc.

## Configuração

### Passo 1: Clonar o repositório

Primeiro, clone este repositório para o seu diretório `htdocs` no XAMPP.


cd /c/xampp/htdocs
git clone https://github.com/SEU_USUARIO/controle-tarefas.git
cd controle-tarefas



### Passo 2: Configurar o Banco de Dados
Abra o painel de controle do XAMPP e inicie o Apache e o MySQL.

Abra o phpMyAdmin indo para http://localhost/phpmyadmin no seu navegador.

Crie um novo banco de dados chamado tarefas_db.

No banco de dados tarefas_db, crie a tabela tarefas com a seguinte estrutura:


CREATE TABLE `tarefas` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `nome` VARCHAR(255) NOT NULL,
  `custo` INT(11) NOT NULL,
  `prazo` DATE NOT NULL,
  `ordem` INT(11) UNSIGNED NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ordem` (`ordem`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


### Passo 3: Configurar a Aplicação
Edite o arquivo config.php e configure a conexão com o banco de dados:


<?php

$dbHost = 'Localhost';
$dbUsername = 'root';
$dbPassword = '';
$dbName = 'lista_tarefas';


$conexao = new mysqli($dbHost,$dbUsername,$dbPassword,$dbName);

?>

### Passo 4: Executar a Aplicação
Abra o seu navegador e acesse http://localhost/controletarefas.

Você deve ver a lista de tarefas com a opção de adicionar, editar, excluir e reordenar tarefas.
