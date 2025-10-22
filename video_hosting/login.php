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

$stmt = $conn->prepare("SELECT id, password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();
$stmt->bind_result($id, $hash);
if ($stmt->fetch()) {
    if (password_verify($password, $hash)) {
        $_SESSION['user_id'] = $id;
        $_SESSION['username'] = $username;
        echo json_encode(['ok' => true, 'msg' => 'Вход успешен.', 'username' => $username]);
    } else {
        echo json_encode(['ok' => false, 'msg' => 'Неверный логин или пароль.']);
    }
} else {
    echo json_encode(['ok' => false, 'msg' => 'Неверный логин или пароль.']);
}
$stmt->close();
$conn->close();
