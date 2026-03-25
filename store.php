<?php
session_start(); // <-- INÍCIO DA SESSÃO

require __DIR__ . "/connect.php";

$name = trim($_POST["name"] ?? "");
$email = trim($_POST["email"] ?? "");
$document = trim($_POST["document"] ?? "");

if ($name === "" || $email === "" || $document === "") {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Preencha todos os campos.'];
    header("Location: index.php");
    exit;
}

$pdo = Connect::getInstance();

// Verificar se o e-mail já está cadastrado
$stmtCheck = $pdo->prepare("SELECT id FROM users WHERE email = :email LIMIT 1");
$stmtCheck->execute([":email" => $email]);
if ($stmtCheck->fetch()) {
    $_SESSION['flash'] = ['type' => 'danger', 'message' => 'Este e-mail já está cadastrado!'];
    header("Location: index.php");
    exit;
}

// Inserir novo aluno
$stmt = $pdo->prepare("INSERT INTO users (name, email, document) VALUES (:name, :email, :document)");
$stmt->execute([
    ":name" => $name,
    ":email" => $email,
    ":document" => $document
]);

$_SESSION['flash'] = ['type' => 'success', 'message' => 'Aluno cadastrado com sucesso!'];
header("Location: index.php");
exit;