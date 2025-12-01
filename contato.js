const faqItems = document.querySelectorAll(".faq-item");

faqItems.forEach(item => {
    item.addEventListener("click", () => {
        item.classList.toggle("active");
    });
});

function mostrarMensagem(texto, erro = false) {
    const msg = document.querySelector("#mensagem-status"); // corrigido
    msg.textContent = texto;
    msg.classList.toggle("erro", erro);
    msg.classList.add("visivel");

    setTimeout(() => {
        msg.classList.remove("visivel");
    }, 3000);
}

window.onload = () => {
    const params = new URLSearchParams(window.location.search);
    const status = params.get("status");

    if (status === "sucesso") {
        mostrarMensagem("Mensagem enviada com sucesso, cheque seu e-mail!", true);
        // console.log("sucesso"); // se quiser logar
    } else if (status === "erro") {
        mostrarMensagem("Mensagem não enviada, tente novamente mais tarde!", true);
    }
};