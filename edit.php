<?php
session_start(); // Para mensagens flash (opcional)
require __DIR__ . "/connect.php";

$id = filter_input(INPUT_GET, "id", FILTER_VALIDATE_INT);
if (!$id) {
    die("ID inválido.");
}

$pdo = Connect::getInstance();
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id LIMIT 1");
$stmt->execute([":id" => $id]);
$user = $stmt->fetch();

if (!$user) {
    die("Aluno não encontrado.");
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Aluno</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="container">
        <div class="card" style="max-width: 700px; margin: 0 auto;">
            <div class="card-header">
                <h1><i class="fas fa-user-edit"></i> Editar Aluno</h1>
                <p>Atualize os dados do aluno cadastrado</p>
            </div>
            <div class="card-body">
                <form action="update.php" method="post">
                    <input type="hidden" name="id" value="<?= $user['id'] ?>">
                    <div class="form-group">
                        <label><i class="fas fa-user"></i> Nome completo</label>
                        <input type="text" name="name" class="form-control"
                            value="<?= htmlspecialchars($user['name']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-envelope"></i> E-mail</label>
                        <input type="email" name="email" class="form-control"
                            value="<?= htmlspecialchars($user['email']) ?>" required>
                    </div>
                    <div class="form-group">
                        <label><i class="fas fa-graduation-cap"></i> Curso</label>
                        <input type="text" name="document" class="form-control"
                            value="<?= htmlspecialchars($user['document']) ?>" required>
                    </div>
                    <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Atualizar</button>
                        <a href="index.php" class="btn btn-secondary"><i class="fas fa-arrow-left"></i> Cancelar</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <script src="script.js"></script>
</body>

</html>