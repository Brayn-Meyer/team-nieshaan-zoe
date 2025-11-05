(function() {
  const toggle = document.getElementById('theme-toggle');
  const body = document.body;
  if (!toggle) return;

  function applyTheme(isDark) {
    body.classList.toggle('dark-mode', isDark);
    toggle.classList.toggle('active', isDark);
    try { localStorage.setItem('theme', isDark ? 'dark' : 'light'); } catch(e){}
  }

  toggle.addEventListener('click', () => {
    applyTheme(!body.classList.contains('dark-mode'));
  });

  // Initialize from storage
  try {
    const saved = localStorage.getItem('theme');
    if (saved === 'dark') applyTheme(true);
  } catch(e){}
})();
