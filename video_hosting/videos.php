<?php
session_start();
require_once 'db.php';
if (!isset($_SESSION['user_id'])) {
  header('Location: index.php');
  exit;
}
$username = $_SESSION['username'] ?? '';
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Видео — Видеохостинг</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <div class="container py-4">
    <div class="app-navbar d-flex justify-content-between align-items-center mb-4">
      <div class="brand-badge">
        <span class="dot"></span>
        <span>VideoHost</span>
      </div>
      <div class="d-flex align-items-center gap-3">
        <span class="text-muted d-none d-sm-inline">Привет, <?=htmlspecialchars($username)?>!</span>
        <a class="btn btn-outline-brand btn-sm" href="logout.php"><i class="bi bi-box-arrow-right me-1"></i>Выйти</a>
      </div>
    </div>

    <section class="card-ui pad mb-4">
      <h2 class="section-title h5 mb-3"><i class="bi bi-upload me-1"></i>Загрузить видео</h2>
      <form id="uploadForm" class="row gy-2 gx-2 align-items-center" enctype="multipart/form-data">
        <div class="col-12 col-md">
          <input class="form-control" name="title" id="videoTitle" placeholder="Название видео" required />
        </div>
        <div class="col-12 col-md">
          <input class="form-control" type="file" name="video_file" id="videoFile" accept="video/*" required />
        </div>
        <div class="col-auto">
          <button class="btn btn-brand" type="submit"><i class="bi bi-cloud-arrow-up me-1"></i>Загрузить</button>
        </div>
        <div class="col-12">
          <progress id="uploadProgress" value="0" max="100" style="display:none;width:100%"></progress>
          <div class="form-text" id="uploadMsg"></div>
        </div>
      </form>
    </section>

    <section class="card-ui pad mb-3">
      <div class="d-flex gap-2 align-items-center flex-wrap">
        <input class="form-control" id="searchInput" placeholder="Поиск по названию" style="max-width:320px" />
        <select id="sortSelect" class="form-select" style="max-width:220px">
          <option value="">Сортировка</option>
          <option value="title_asc">Название ↑</option>
          <option value="title_desc">Название ↓</option>
          <option value="likes_desc">Лайки ↓</option>
          <option value="date_desc">Дата ↓</option>
          <option value="date_asc">Дата ↑</option>
        </select>
        <button id="searchBtn" class="btn btn-outline-brand"><i class="bi bi-search me-1"></i>Найти</button>
      </div>
    </section>

    <section>
      <div id="videosList" class="row g-3"></div>
    </section>

    <footer class="text-center text-muted small py-4">© VideoHost</footer>
  </div>
  <script src="js/app.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
