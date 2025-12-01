const senhaInput = document.getElementById('senha');
const confirmarSenhaInput = document.getElementById('confirmar_senha');
const erroElement = document.getElementById('erro-senha');

function validarSenhasEmTempoReal() {
    const senha = senhaInput.value;
    const confirmarSenha = confirmarSenhaInput.value;
    let mensagemErro = '';

    if (senha.length > 0 && senha.length < 8) {
        mensagemErro = "A senha deve ter pelo menos 8 caracteres.";
    } else if (confirmarSenha.length > 0 && senha !== confirmarSenha) {
        mensagemErro = "As senhas não coincidem.";
    }

    erroElement.innerText = mensagemErro;
}

senhaInput.addEventListener('keyup', validarSenhasEmTempoReal);
confirmarSenhaInput.addEventListener('keyup', validarSenhasEmTempoReal);

function validarForms(event) {
    validarSenhasEmTempoReal(); 

    if (erroElement.innerText !== '') {
        event.preventDefault(); 
        mostrarErroToast(erroElement.innerText);
        return false;
    }

    return true; 
}

function mostrarErroToast(mensagem, duracao = 3000) {
    const toastElement = document.getElementById('toast-erro');
    const mensagemElement = document.getElementById('toast-mensagem');
    
    if (!toastElement || !mensagemElement) return;

    mensagemElement.innerText = mensagem;
    toastElement.classList.add('show');
    
    setTimeout(() => {
        toastElement.classList.remove('show');
    }, duracao);
}

window.onload = () => {
    const params = new URLSearchParams(window.location.search);
    const erro = params.get("erro");

    if (erro === "1") {
        mostrarErroToast("CPF já cadastrado!")
    } else if (erro === "2") {
        mostrarErroToast("E-mail já cadastrado!")
    }
};

document.getElementById("telefone").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, ""); // remove tudo que não é número

    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 6) {
        e.target.value = value.replace(/^(\d{2})(\d{5})(\d{0,4}).*/, "($1) $2-$3");
    } else if (value.length > 2) {
        e.target.value = value.replace(/^(\d{2})(\d{0,5}).*/, "($1) $2");
    } else {
        e.target.value = value.replace(/^(\d{0,2}).*/, "($1");
    }
});

// Formatador de CPF
document.getElementById("cpf").addEventListener("input", function (e) {
    let value = e.target.value.replace(/\D/g, "");

    if (value.length > 11) value = value.slice(0, 11);

    if (value.length > 9) {
        e.target.value = value.replace(/^(\d{3})(\d{3})(\d{3})(\d{0,2}).*/, "$1.$2.$3-$4");
    } else if (value.length > 6) {
        e.target.value = value.replace(/^(\d{3})(\d{3})(\d{0,3}).*/, "$1.$2.$3");
    } else if (value.length > 3) {
        e.target.value = value.replace(/^(\d{3})(\d{0,3}).*/, "$1.$2");
    } else {
        e.target.value = value;
    }
});

// Capitalizar nome automaticamente
document.getElementById("nome").addEventListener("blur", function (e) {
    e.target.value = e.target.value
        .toLowerCase()
        .replace(/\b\w/g, c => c.toUpperCase());
});