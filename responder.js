document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("form-resposta");
    const modalSucesso = document.getElementById("modal-sucesso");

    form.addEventListener("submit", (e) => {
        e.preventDefault();

        const emailUsuario = document.getElementById("email").value;
        const nomeUsuario = document.getElementById("nome").value;
        const assunto = document.getElementById("assunto").value;
        const resposta = document.getElementById("resposta").value;

        emailjs.send("service_dn2sl3i", "template_resposta_admin", {
            email: emailUsuario,
            name: nomeUsuario,
            title: assunto,
            resposta: resposta
        })
        .then(() => {
            modalSucesso.classList.add("mostrar");
            setTimeout(() => {
                form.submit();
            }, 1500);
        })
        .catch(() => {
            alert("Erro ao enviar resposta por e-mail.");
            form.submit();
        });
    });
});
