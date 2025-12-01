// Validação simples do formulário de recuperação
document.getElementById("recuperarForm").addEventListener("submit", function(e) {
  e.preventDefault();

  const email = document.getElementById("email").value.trim();

  if (email === "") {
    alert("Por favor, digite seu e-mail!");
    return;
  }

  if (!email.includes("@")) {
    alert("Digite um e-mail válido!");
    return;
  }

  alert("Se este e-mail estiver cadastrado, enviaremos instruções para recuperação de senha.");
  document.getElementById("recuperarForm").reset();
});
