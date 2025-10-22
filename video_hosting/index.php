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
  <title>Видеохостинг — вход и регистрация</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="css/style.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
</head>
<body>
  <div class="container py-4 landing-wrap">
    <div class="app-navbar d-flex justify-content-between align-items-center mb-4">
      <div class="brand-badge">
        <span class="dot"></span>
        <span>VideoHost</span>
      </div>
      
    </div>

    <section class="hero hero-full card-ui pad mb-4">
      <div class="row align-items-center g-3">
        <div class="col-12 col-lg-6">
          <h1 class="display-6 mb-2">Загружай видео и делись с друзьями</h1>
          <p class="text-secondary mb-3">Простой и быстрый видеохостинг: регистрация, загрузка, поиск, лайки.</p>
        </div>
        <div class="col-12 col-lg-6">
          <div class="row g-3">
            <div class="col-12">
              <div class="card-ui pad">
                <h2 class="h5 mb-2"><i class="bi bi-box-arrow-in-right me-1"></i>Вход</h2>
                <form id="loginForm" class="d-flex flex-column gap-2">
                  <input class="form-control" name="username" placeholder="Email" />
                  <input class="form-control" name="password" type="password" placeholder="Пароль" />
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="form-text text-danger" id="loginMsg"></div>
                    <button class="btn btn-brand" type="submit">Войти</button>
                  </div>
                </form>
              </div>
            </div>
            <div class="col-12">
              <div class="card-ui pad">
                <h2 class="h5 mb-2"><i class="bi bi-person-plus me-1"></i>Регистрация</h2>
                <form id="registerForm" class="d-flex flex-column gap-2">
                  <input class="form-control" name="username" placeholder="Email" />
                  <input class="form-control" name="password" type="password" placeholder="Пароль" />
                  <div class="d-flex justify-content-between align-items-center">
                    <div class="form-text text-danger" id="registerMsg"></div>
                    <button class="btn btn-outline-brand" type="submit">Создать аккаунт</button>
                  </div>
                </form>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <footer class="text-center text-muted small py-3">© VideoHost</footer>
  </div>
  <script src="js/auth.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
