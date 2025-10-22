
<?php
// videos_list.php — отдаёт список видео, пометка liked для текущего пользователя
session_start();
header('Content-Type: application/json; charset=utf-8');
require_once 'db.php';

$search = trim($_GET['search'] ?? '');
$sort = $_GET['sort'] ?? '';

$allowedSort = [
    'title_asc' => 'v.title ASC',
    'title_desc' => 'v.title DESC',
    'likes_desc' => 'v.likes DESC',
    'date_desc' => 'v.upload_date DESC',
    'date_asc' => 'v.upload_date ASC'
];
$order = $allowedSort[$sort] ?? 'v.upload_date DESC';

$logged = isset($_SESSION['user_id']);
$user_id = $logged ? (int)$_SESSION['user_id'] : 0;

$sql = "SELECT v.id, v.title, v.filename, v.likes, v.upload_date, u.username";
if ($logged) {
    $sql .= ", (SELECT COUNT(*) FROM video_likes vl WHERE vl.video_id = v.id AND vl.user_id = ?) AS liked";
} else {
    $sql .= ", 0 AS liked";
}
$sql .= " FROM videos v JOIN users u ON u.id = v.user_id";

$params = [];
$types = "";

if ($search !== '') {
    $sql .= " WHERE v.title LIKE ?";
}

$sql .= " ORDER BY $order LIMIT 100";

$stmt = $conn->prepare($sql);

if ($logged && $search !== '') {
    $types = "is";
    $like = '%' . $search . '%';
    $stmt->bind_param($types, $user_id, $like);
} elseif ($logged) {
    $types = "i";
    $stmt->bind_param($types, $user_id);
} elseif ($search !== '') {
    $types = "s";
    $like = '%' . $search . '%';
    $stmt->bind_param($types, $like);
}

$stmt->execute();
$res = $stmt->get_result();
$videos = [];
while ($row = $res->fetch_assoc()) {
    $row['liked'] = (int)$row['liked'];
    $videos[] = $row;
}
echo json_encode(['videos' => $videos]);
$stmt->close();
$conn->close();
