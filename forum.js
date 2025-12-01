const modal = document.getElementById('modalMensagem');
const btnAbrir = document.getElementById('btnAbrirModal');
const btnFechar = modal.querySelector('.fechar');

// Abrir modal
btnAbrir.addEventListener('click', () => {
  modal.style.display = 'block';
});

// Fechar modal
btnFechar.addEventListener('click', () => {
  modal.style.display = 'none';
});

// Fechar clicando fora do conteúdo
window.addEventListener('click', (e) => {
  if (e.target === modal) {
    modal.style.display = 'none';
  }
});
