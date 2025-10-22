<?php
session_start();
if (isset($_SESSION['user_id'])) {
  header('Location: videos.php');
  exit;
}
?>
<!doctype html>
<html lang="ru">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Авторизация — Видеохостинг</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
</head>
<body>
  <div class="container py-5">
    <h1 class="h3 mb-4 text-center">Видеохостинг — вход и регистрация</h1>
    <div class="row g-4 justify-content-center">
      <div class="col-12 col-md-5">
        <div class="card p-3">
          <h2 class="h5">Вход</h2>
          <form id="loginForm" class="d-flex flex-column gap-2">
            <input class="form-control" name="username" placeholder="Email" />
            <input class="form-control" name="password" type="password" placeholder="Пароль" />
            <button class="btn btn-primary" type="submit">Войти</button>
            <div class="form-text text-danger" id="loginMsg"></div>
          </form>
        </div>
      </div>
      <div class="col-12 col-md-5">
        <div class="card p-3">
          <h2 class="h5">Регистрация</h2>
          <form id="registerForm" class="d-flex flex-column gap-2">
            <input class="form-control" name="username" placeholder="Email" />
            <input class="form-control" name="password" type="password" placeholder="Пароль" />
            <button class="btn btn-success" type="submit">Зарегистрироваться</button>
            <div class="form-text text-danger" id="registerMsg"></div>
          </form>
        </div>
      </div>
    </div>
  </div>
  <script src="js/auth.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
