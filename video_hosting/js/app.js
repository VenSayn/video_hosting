document.addEventListener('DOMContentLoaded', () => {
  const uploadForm = document.getElementById('uploadForm');
  const videosList = document.getElementById('videosList');
  const uploadProgress = document.getElementById('uploadProgress');
  const searchBtn = document.getElementById('searchBtn');
  const searchInput = document.getElementById('searchInput');
  const sortSelect = document.getElementById('sortSelect');

  const showMsg = (el, msg, ok=true) => {
    if (!el) return;
    el.textContent = msg;
    el.classList.toggle('text-danger', !ok);
    el.classList.toggle('text-success', ok);
    setTimeout(()=>{ el.textContent = ''; }, 4000);
  };

  function styleLikeButton(btn, liked) {
    if (liked) {
      btn.classList.remove('btn-outline-danger');
      btn.classList.add('btn-danger');
      btn.title = 'Нажмите, чтобы убрать лайк';
    } else {
      btn.classList.remove('btn-danger');
      btn.classList.add('btn-outline-danger');
      btn.title = 'Нажмите, чтобы поставить лайк';
    }
  }

  const renderVideos = (videos) => {
    videosList.innerHTML = '';
    if (!videos.length) {
      videosList.innerHTML = '<div class="col-12"><div class="alert alert-secondary">Видео не найдены.</div></div>';
      return;
    }
    videos.forEach(v => {
      const col = document.createElement('div');
      col.className = 'col-12 col-sm-6';
      const card = document.createElement('div');
      card.className = 'video-card';
      const h3 = document.createElement('h5');
      h3.textContent = v.title;
      const meta = document.createElement('div');
      meta.className = 'video-meta mb-2';
      meta.textContent = `Загружено: ${v.upload_date} · Автор: ${v.username}`;
      const videoEl = document.createElement('video');
      videoEl.controls = true;
      videoEl.className = 'w-100';
      videoEl.src = 'videos/' + v.filename;
      const likeBtn = document.createElement('button');
      likeBtn.className = 'btn btn-outline-danger btn-sm mt-2 btn-like';
      likeBtn.innerHTML = `❤ <span class="likes-count">${v.likes}</span>`;
      styleLikeButton(likeBtn, Number(v.liked) > 0);

      likeBtn.addEventListener('click', async () => {
        const fd = new FormData();
        fd.append('video_id', String(v.id));
        const res = await fetch('like.php', { method: 'POST', body: fd, credentials: 'same-origin' });
        const json = await res.json();
        if (json.ok) {
          const span = likeBtn.querySelector('.likes-count');
          if (span) span.textContent = String(json.likes);
          styleLikeButton(likeBtn, !!json.liked);
          // локально обновим модель, чтобы при повторной перерисовке было корректно
          v.liked = json.liked ? 1 : 0;
          v.likes = json.likes;
        } else {
          alert(json.msg || 'Ошибка лайка');
        }
      });

      card.append(h3, meta, videoEl, likeBtn);
      col.append(card);
      videosList.append(col);
    });
  };

  const loadVideos = async () => {
    const params = new URLSearchParams();
    const s = (searchInput?.value || '').trim();
    const sort = sortSelect?.value || '';
    if (s) params.append('search', s);
    if (sort) params.append('sort', sort);
    const res = await fetch('videos_list.php?' + params.toString(), { credentials: 'same-origin' });
    const json = await res.json();
    renderVideos(json.videos || []);
  };

  if (uploadForm) {
    uploadForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const title = document.getElementById('videoTitle').value.trim();
      const fileInp = document.getElementById('videoFile');
      const msgEl = document.getElementById('uploadMsg');
      if (!title) { showMsg(msgEl, 'Введите название.', false); return; }
      if (!fileInp.files?.length) { showMsg(msgEl, 'Выберите файл.', false); return; }
      const file = fileInp.files[0];
      if (file.size > 200 * 1024 * 1024) { showMsg(msgEl, 'Файл слишком большой.', false); return; }

      const fd = new FormData();
      fd.append('title', title);
      fd.append('video_file', file);

      if (uploadProgress) {
        uploadProgress.style.display = 'block';
        uploadProgress.value = 0;
      }

      const xhr = new XMLHttpRequest();
      xhr.open('POST', 'upload.php');
      xhr.withCredentials = true
      xhr.upload.addEventListener('progress', (ev) => {
        if (ev.lengthComputable && uploadProgress) {
          uploadProgress.value = Math.round((ev.loaded / ev.total) * 100);
        }
      });
      xhr.onload = async () => {
        if (uploadProgress) uploadProgress.style.display = 'none';
        try {
          const json = JSON.parse(xhr.responseText);
          showMsg(msgEl, json.msg || '', !!json.ok);
          if (json.ok) {
            uploadForm.reset();
            await loadVideos();
          }
        } catch (e) {
          showMsg(msgEl, 'Ошибка сервера.', false);
        }
      };
      xhr.send(fd);
    });
  }

  if (searchBtn) searchBtn.addEventListener('click', loadVideos);
  if (sortSelect) sortSelect.addEventListener('change', loadVideos);

  loadVideos();
});