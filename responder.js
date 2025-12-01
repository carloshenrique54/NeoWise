const modal = document.getElementById('responderModal');
const closeModal = modal.querySelector('.close');
const buttons = document.querySelectorAll('.responder-btn');

buttons.forEach(btn => {
    btn.addEventListener('click', () => {
        document.getElementById('modal_id').value = btn.dataset.id;
        document.getElementById('modal_assunto').textContent = btn.dataset.assunto;
        document.getElementById('modal_mensagem').textContent = btn.dataset.mensagem;
        document.getElementById('modal_resposta').value = '';
        modal.style.display = 'block';
    });
});

closeModal.onclick = () => modal.style.display = 'none';
window.onclick = e => { if(e.target == modal) modal.style.display = 'none'; };