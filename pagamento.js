const radios = document.querySelectorAll('input[name="metodo_pagamento"]');
const pagamentoCartao = document.getElementById("Pagamento-cartao");
const pagamentoPix = document.getElementById("Pagamento-pix");
const pagamentoBoleto = document.getElementById("Pagamento-boleto");
const mensagem = document.getElementById("mensagem");

// Pega todos os campos do cartão
const camposCartao = pagamentoCartao.querySelectorAll("input");

// Função pra limpar required de tudo
function limparRequired() {
  camposCartao.forEach(campo => campo.required = false);
}

radios.forEach((radio) => {
  radio.addEventListener("change", () => {
    limparRequired();

    switch (radio.value) {
      case "cartao":
        pagamentoCartao.style.display = "flex";
        pagamentoPix.style.display = "none";
        pagamentoBoleto.style.display = "none";
        camposCartao.forEach(campo => campo.required = true);
        break;

      case "pix":
        pagamentoCartao.style.display = "none";
        pagamentoPix.style.display = "flex";
        pagamentoBoleto.style.display = "none";
        break;

      case "boleto":
        pagamentoCartao.style.display = "none";
        pagamentoPix.style.display = "none";
        pagamentoBoleto.style.display = "flex";
        break;
    }
  });
});

document.querySelector("form").addEventListener("submit", (e) => {
  const metodo = document.querySelector('input[name="metodo_pagamento"]:checked')?.value;
  
  if (metodo !== "cartao") limparRequired();
});

function formatarTelefone(valor) {
    valor = valor.replace(/\D/g, "");

    if (valor.length > 11) valor = valor.slice(0, 11);

    if (valor.length <= 10) {
        return valor.replace(/(\d{2})(\d{4})(\d{0,4})/, "($1) $2-$3");
    } else {
        return valor.replace(/(\d{2})(\d{5})(\d{0,4})/, "($1) $2-$3");
    }
}

function formatarCPF(valor) {
    valor = valor.replace(/\D/g, "");

    if (valor.length > 11) valor = valor.slice(0, 11);

    return valor
        .replace(/(\d{3})(\d)/, "$1.$2")
        .replace(/(\d{3})(\d)/, "$1.$2")
        .replace(/(\d{3})(\d{1,2})$/, "$1-$2");
}

document.getElementById("telefone").addEventListener("input", function () {
    this.value = formatarTelefone(this.value);
});

document.getElementById("cpf").addEventListener("input", function () {
    this.value = formatarCPF(this.value);
})

window.onload = function() {
    const urlParams = new URLSearchParams(window.location.search);
    const erro = urlParams.get('erro');
    if (erro === '1') {
        let mensagemErro = 'Selecione um método de pagamento.';
        if (erro === '2') {
            mensagemErro = 'CPF não cadastrado como aluno.';
        }
      }
    }

document.getElementById("numero-cartao").addEventListener("input", (e) => {
    let v = e.target.value.replace(/\D/g, "");       // só números
    v = v.replace(/(\d{4})(?=\d)/g, "$1 ");          // coloca espaço a cada 4 digitos
    e.target.value = v.substring(0, 19);             // limita
});

// Validade do cartão
document.getElementById("validade-cartao").addEventListener("input", (e) => {
    let v = e.target.value.replace(/\D/g, "");       // só números
    if (v.length >= 3) {
        v = v.replace(/(\d{2})(\d{1,2})/, "$1/$2");  // MM/AA
    }
    e.target.value = v.substring(0, 5);
});

// CVV
document.getElementById("cvv-cartao").addEventListener("input", (e) => {
    let v = e.target.value.replace(/\D/g, "");       // só números
    e.target.value = v.substring(0, 3);              // máx 3 dígitos
});