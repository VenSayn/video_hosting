<?php
// like.php — toggle: повторное нажатие снимает лайк
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['ok' => false, 'msg' => 'Требуется авторизация.']);
    exit;
}

$user_id = (int)$_SESSION['user_id'];
$video_id = intval($_POST['video_id'] ?? 0);
if ($video_id <= 0) {
    echo json_encode(['ok' => false, 'msg' => 'Неверный ID видео.']);
    exit;
}

// Проверим, лайкал ли уже пользователь это видео
$chk = $conn->prepare("SELECT 1 FROM video_likes WHERE user_id = ? AND video_id = ?");
$chk->bind_param("ii", $user_id, $video_id);
$chk->execute();
$chk->store_result();
$already = $chk->num_rows > 0;
$chk->close();

if ($already) {
    // Снимаем лайк: удаляем запись и уменьшаем счетчик (но не ниже нуля)
    $del = $conn->prepare("DELETE FROM video_likes WHERE user_id = ? AND video_id = ?");
    $del->bind_param("ii", $user_id, $video_id);
    if (!$del->execute()) {
        echo json_encode(['ok' => false, 'msg' => 'Ошибка при снятии лайка: ' . $conn->error]);
        exit;
    }
    $del->close();

    $upd = $conn->prepare("UPDATE videos SET likes = GREATEST(likes - 1, 0) WHERE id = ?");
    $upd->bind_param("i", $video_id);
    if (!$upd->execute()) {
        echo json_encode(['ok' => false, 'msg' => 'Ошибка при обновлении лайка: ' . $conn->error]);
        exit;
    }
    $upd->close();
    $liked = false;
} else {
    // Ставим лайк: вставляем запись и увеличиваем счетчик
    $ins = $conn->prepare("INSERT INTO video_likes (user_id, video_id) VALUES (?, ?)");
    $ins->bind_param("ii", $user_id, $video_id);
    if (!$ins->execute()) {
        echo json_encode(['ok' => false, 'msg' => 'Ошибка при добавлении лайка: ' . $conn->error]);
        exit;
    }
    $ins->close();

    $upd = $conn->prepare("UPDATE videos SET likes = likes + 1 WHERE id = ?");
    $upd->bind_param("i", $video_id);
    if (!$upd->execute()) {
        echo json_encode(['ok' => false, 'msg' => 'Ошибка при обновлении лайка: ' . $conn->error]);
        exit;
    }
    $upd->close();
    $liked = true;
}

// Вернём актуальный счетчик и флаг liked
$q = $conn->prepare("SELECT likes FROM videos WHERE id = ?");
$q->bind_param("i", $video_id);
$q->execute();
$q->bind_result($likes);
$q->fetch();
$q->close();

echo json_encode(['ok' => true, 'likes' => $likes, 'liked' => $liked]);
$conn->close();
