const mensagem = document.getElementById('mensagem');

function validarForms(event) {
    event.preventDefault(); // Impede o envio padrão do formulário

    const email = document.getElementById('email').value;
    const senha = document.getElementById('senha').value;

    if (!email || !senha) {
        alert('Por favor, preencha todos os campos.');
        return false;
    } 
}

const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('status') === 'sucesso') {
      alert('✅ Cadastro realizado com sucesso! Faça login para continuar.');
    }

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('erro') === '1') {
        mensagem.style.display = 'block';
        mensagem.textContent = 'Email ou senha incorretos. Tente novamente.';
    }
    if (urlParams.get('erro') === '2') {
        mensagem.style.display = 'block';
        mensagem.textContent = 'Usuário não encontrado. Verifique o email digitado.';
    }
};