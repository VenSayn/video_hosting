<?php
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['ok' => false, 'msg' => 'Требуется авторизация.']);
    exit;
}

$title = trim($_POST['title'] ?? '');
if ($title === '') {
    echo json_encode(['ok' => false, 'msg' => 'Название не может быть пустым.']);
    exit;
}

if (!isset($_FILES['video_file']) || $_FILES['video_file']['error'] !== UPLOAD_ERR_OK) {
    echo json_encode(['ok' => false, 'msg' => 'Ошибка загрузки файла.']);
    exit;
}

$file = $_FILES['video_file'];
$maxSize = 200 * 1024 * 1024;
if ($file['size'] > $maxSize) {
    echo json_encode(['ok' => false, 'msg' => 'Файл слишком большой (макс 200MB).']);
    exit;
}

$allowed = ['mp4','webm','ogg','mov','mkv'];
$ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
if (!in_array($ext, $allowed)) {
    echo json_encode(['ok' => false, 'msg' => 'Недопустимый формат видео.']);
    exit;
}

$videosDir = __DIR__ . '/videos';
if (!is_dir($videosDir)) mkdir($videosDir, 0755, true);

$uniq = time() . '_' . bin2hex(random_bytes(6)) . '.' . $ext;
$dest = $videosDir . '/' . $uniq;

if (!move_uploaded_file($file['tmp_name'], $dest)) {
    echo json_encode(['ok' => false, 'msg' => 'Не удалось сохранить файл.']);
    exit;
}

$stmt = $conn->prepare("INSERT INTO videos (user_id, title, filename) VALUES (?, ?, ?)");
$stmt->bind_param("iss", $_SESSION['user_id'], $title, $uniq);
if ($stmt->execute()) {
    echo json_encode(['ok' => true, 'msg' => 'Видео загружено.', 'video_id' => $stmt->insert_id]);
} else {
    @unlink($dest);
    echo json_encode(['ok' => false, 'msg' => 'Ошибка при сохранении в базе.']);
}
$stmt->close();
$conn->close();
