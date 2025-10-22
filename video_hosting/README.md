# Видеохостинг (две страницы)

- **auth.php** — страница авторизации (вход/регистрация).
- **videos.php** — страница с поиском, сортировкой, загрузкой и лайками (только для авторизованных).

## Запуск под XAMPP
1. Распакуйте в `C:\xampp\htdocs\video_hosting_split\`
2. Запустите Apache и MySQL в XAMPP
3. Создайте БД `video_hosting`, импортируйте `sql/video_hosting.sql`
4. Проверьте `db.php` (root/пустой пароль)
5. Откройте `start_local.html` или перейдите на `http://localhost/video_hosting_split/auth.php`

## Файлы
- `auth.php`, `videos.php`
- API: `register.php`, `login.php`, `logout.php`, `upload.php`, `like.php`, `videos_list.php`
- Статика: `js/auth.js`, `js/app.js`, `css/style.css`
- Данные: `sql/video_hosting.sql`


### Защита от повторных лайков
- Добавлена таблица `video_likes (user_id, video_id)` с уникальным ключом.
- Сервер блокирует повторные лайки и возвращает сообщение.
- На странице видео кнопка лайка отключается, если пользователь уже лайкнул.


### Лайк/анлайк (toggle)
- Повторное нажатие на лайк снимает лайк.
- Сервер возвращает `liked: true|false` и актуальные `likes`.
- Кнопка меняет стиль (`btn-outline-danger` ↔ `btn-danger`).
