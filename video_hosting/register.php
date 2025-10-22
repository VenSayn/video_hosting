<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$username = trim($_POST['username'] ?? '');
$password = (string)($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    echo json_encode(['ok' => false, 'msg' => 'Поля не должны быть пустыми.']);
    exit;
}

if (!filter_var($username, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['ok' => false, 'msg' => 'Введите корректный e-mail.']);
    exit;
}

$stmt = $conn->prepare("SELECT id FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->store_result();
if ($stmt->num_rows > 0) {
    echo json_encode(['ok' => false, 'msg' => 'Пользователь с таким email уже существует.']);
    exit;
}
$stmt->close();

$hash = password_hash($password, PASSWORD_DEFAULT);
$stmt = $conn->prepare("INSERT INTO users (username, password) VALUES (?, ?)");
$stmt->bind_param("ss", $username, $hash);
if ($stmt->execute()) {
    $_SESSION['user_id'] = $stmt->insert_id;
    $_SESSION['username'] = $username;
    echo json_encode(['ok' => true, 'msg' => 'Регистрация успешна.']);
} else {
    echo json_encode(['ok' => false, 'msg' => 'Ошибка при регистрации.']);
}
$stmt->close();
$conn->close();
