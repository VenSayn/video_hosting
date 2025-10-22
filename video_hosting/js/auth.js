document.addEventListener('DOMContentLoaded', () => {
  const loginForm = document.getElementById('loginForm');
  const registerForm = document.getElementById('registerForm');

  const showMsg = (el, msg, ok=true) => {
    if (!el) return;
    el.textContent = msg;
    el.classList.toggle('text-danger', !ok);
    el.classList.toggle('text-success', ok);
    setTimeout(()=>{ el.textContent = ''; }, 4000);
  };

  const postForm = async (url, form) => {
    const fd = new FormData(form);
    const res = await fetch(url, { method: 'POST', body: fd, credentials: 'same-origin' });
    return res.json();
  };

  if (loginForm) {
    loginForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const json = await postForm('login.php', loginForm);
      const msgEl = document.getElementById('loginMsg');
      showMsg(msgEl, json.msg || '', !!json.ok);
      if (json.ok) window.location.href = 'videos.php';
    });
  }

  if (registerForm) {
    registerForm.addEventListener('submit', async (e) => {
      e.preventDefault();
      const json = await postForm('register.php', registerForm);
      const msgEl = document.getElementById('registerMsg');
      showMsg(msgEl, json.msg || '', !!json.ok);
      if (json.ok) window.location.href = 'videos.php';
    });
  }
});