<?php
session_start();
require __DIR__ . "/connect.php";

$id = filter_input(INPUT_POST, "id", FILTER_VALIDATE_INT);
$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$document = trim($_POST["document"] ?? "");

if (!$id || $name === "" || $email === "" || $document === "") {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Dados inválidos.'];
    header("Location: index.php");
    exit;
}

$pdo = Connect::getInstance();

// Verifica se o e-mail já está cadastrado para outro usuário
$stmtCheck = $pdo->prepare("SELECT id FROM users WHERE email = :email AND id != :id");
$stmtCheck->execute([':email' => $email, ':id' => $id]);
if ($stmtCheck->fetch()) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Este e-mail já está cadastrado para outro aluno.'];
    header("Location: edit.php?id=" . $id); // volta para edição
    exit;
}

// Atualiza os dados
$stmt = $pdo->prepare("UPDATE users SET name = :name, email = :email, document = :document WHERE id = :id");
$stmt->execute([
    ":id" => $id,
    ":name" => $name,
    ":email" => $email,
    ":document" => $document
]);

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Edição concluída com sucesso!'];
header("Location: index.php");
exit;