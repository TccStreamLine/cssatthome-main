<?php
session_start();
include_once('config.php');

if (empty($_SESSION['id'])) {
    header('Location: login.php');
    exit;
}

$modo_edicao = false;
$categoria_para_editar = [];
$titulo_pagina = "Cadastrar Categoria";

if (isset($_GET['id']) && !empty($_GET['id'])) {
    $modo_edicao = true;
    $id = filter_var($_GET['id'], FILTER_VALIDATE_INT);
    $titulo_pagina = "Editar Categoria";

    $stmt = $pdo->prepare("SELECT * FROM categorias WHERE id = :id");
    $stmt->execute([':id' => $id]);
    $categoria_para_editar = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$categoria_para_editar) {
        $_SESSION['msg_erro'] = "Categoria não encontrada.";
        header('Location: categorias.php');
        exit;
    }
}

$nome_empresa = $_SESSION['nome_empresa'] ?? 'Empresa';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title><?= $titulo_pagina ?> - Streamline</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="css/sistema.css">
    <link rel="stylesheet" href="css/formularios.css"> 
</head>
<body>
    <nav class="sidebar">
        </nav>

    <main class="main-content">
        <header class="main-header">
            <h2>Estoque > <?= $titulo_pagina ?></h2>
            <div class="user-profile"><span><?= htmlspecialchars($nome_empresa) ?></span><div class="avatar"><i class="fas fa-user"></i></div></div>
        </header>

        <div class="form-container">
            <h3><?= $modo_edicao ? 'EDITAR CATEGORIA' : 'CADASTRO DE CATEGORIA' ?></h3>
            <form action="processa_categoria.php" method="POST">
                <input type="hidden" name="acao" value="<?= $modo_edicao ? 'editar' : 'cadastrar' ?>">
                <input type="hidden" name="categoria_id" value="<?= $categoria_para_editar['id'] ?? '' ?>">
                
                <div class="form-group">
                    <input type="text" id="nome" name="nome" required value="<?= htmlspecialchars($categoria_para_editar['nome'] ?? '') ?>" placeholder=" ">
                    <label for="nome">Nome da Categoria</label>
                </div>
                
                <div class="form-actions">
                    <button type="submit" class="btn-primary"><?= $modo_edicao ? 'SALVAR ALTERAÇÕES' : 'CADASTRAR AQUI' ?></button>
                </div>
            </form>
        </div>
    </main>
</body>
</html>