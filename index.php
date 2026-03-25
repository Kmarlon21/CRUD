<?php
session_start(); // <-- INÍCIO DA SESSÃO

require __DIR__ . "/connect.php";

$pdo = Connect::getInstance();
$stmt = $pdo->query("SELECT * FROM users ORDER BY id ASC");
$users = $stmt->fetchAll();
if (!$users) {
    $users = [];
}

// Recupera e apaga mensagem flash
$flash = $_SESSION['flash'] ?? null;
unset($_SESSION['flash']);
?>
<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRUD Master - Sistema de Cadastro</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>

<body>
    <div class="container">
        <?php if ($flash): ?>
            <div class="alert alert-<?= $flash['type'] ?>">
                <i class="fas fa-<?= $flash['type'] === 'success' ? 'check-circle' : 'exclamation-circle' ?>"></i>
                <?= htmlspecialchars($flash['message']) ?>
            </div>
        <?php endif; ?>

        <!-- Stats Cards -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-users"></i></div>
                <div class="stat-number"><?= count($users) ?></div>
                <div class="stat-label">Total de Alunos</div>
            </div>
            <div class="stat-card">
                <div class="stat-icon"><i class="fas fa-book"></i></div>
                <div class="stat-number">
                    <?php
                    $uniqueDocs = array_unique(array_column($users, 'document'));
                    echo count($uniqueDocs);
                    ?>
                </div>
                <div class="stat-label">Cursos Únicos</div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="card">
            <div class="card-header">
                <h1><i class="fas fa-graduation-cap"></i> Cadastro de Alunos</h1>
                <p>Preencha os dados abaixo para cadastrar um novo aluno</p>
            </div>
            <div class="card-body">
                <form action="store.php" method="post">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nome completo</label>
                        <input type="text" name="name" class="form-control" placeholder="Digite o nome completo"
                            required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email" class="form-control" placeholder="exemplo@email.com" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-id-card"></i> Curso</label>
                        <input type="text" name="document" class="form-control"
                            placeholder="Ex.: Análise e Desenvolvimento de Sistemas" required>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Cadastrar Aluno
                    </button>
                </form>
            </div>
        </div>

        <!-- List Card -->
        <div class="card">
            <div class="card-header">
                <h1><i class="fas fa-users"></i> Lista de Alunos</h1>
                <p>Gerencie todos os alunos cadastrados no sistema</p>
            </div>
            <div class="card-body">
                <div class="search-bar">
                    <input type="text" id="searchInput" class="search-input"
                        placeholder="🔍 Buscar aluno por nome, email ou documento...">
                </div>
                <?php if (count($users) > 0): ?>
                    <div class="table-container">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nome</th>
                                    <th>E-mail</th>
                                    <th>Curso</th>
                                    <th>Cadastro</th>
                                    <th>Ações</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($users as $user): ?>
                                    <tr>
                                        <td><span class="badge badge-success">#<?= htmlspecialchars($user["id"]) ?></span></td>
                                        <td><strong><?= htmlspecialchars($user["name"]) ?></strong></td>
                                        <td><?= htmlspecialchars($user["email"]) ?></td>
                                        <td><?= htmlspecialchars($user["document"]) ?></td>
                                        <td><?= date("d/m/Y H:i", strtotime($user["created_at"])) ?></td>
                                        <td>
                                            <a href="edit.php?id=<?= $user["id"] ?>" class="btn btn-warning"
                                                style="padding: 6px 12px; display: inline-block; margin: 2px;">
                                                <i class="fas fa-edit"></i> Editar
                                            </a>
                                            <a href="delete.php?id=<?= $user["id"] ?>" class="btn btn-danger delete-link"
                                                style="padding: 6px 12px; display: inline-block; margin: 2px;">
                                                <i class="fas fa-trash"></i> Excluir
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="6" style="text-align: center; font-weight: bold;">
                                        <i class="fas fa-chart-line"></i> Total de alunos cadastrados: <?= count($users) ?>
                                    </td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info" style="text-align: center; padding: 40px;">
                        <i class="fas fa-info-circle" style="font-size: 48px; margin-bottom: 10px;"></i>
                        <h3>Nenhum aluno cadastrado</h3>
                        <p>Comece cadastrando o primeiro aluno no formulário acima!</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <script src="script.js"></script>

    <style>
        .alert-info {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            color: #0c4a6e;
            border-radius: 12px;
            padding: 30px;
        }

        .alert-info i {
            color: #0284c7;
        }

        .alert-info h3 {
            margin: 10px 0;
            font-size: 20px;
        }

        .alert-info p {
            margin: 0;
            opacity: 0.8;
        }
    </style>
</body>

</html>