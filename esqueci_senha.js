(function () {
    emailjs.init("yexqvxDUHrrXPU6Wf");
})();

const form = document.getElementById("reset-form");
const toast = document.getElementById("toast");
const forgotPasswordBtn = document.getElementById("forgot-password");
const modal = document.getElementById("modal-reset");
const closeModalBtn = document.getElementById("close-modal");

forgotPasswordBtn.addEventListener("click", () => {
    modal.style.display = "flex";
});

closeModalBtn.addEventListener("click", () => {
    modal.style.display = "none";
});

form.addEventListener("submit", (e) => {
    e.preventDefault();

    const userEmail = document.getElementById("email-modal").value;

    if (!userEmail) {
        mostrarToast("Digite um e-mail válido.");
        return;
    }

    const token = Date.now();
    const resetLink = `file:///D:/Projeto%20Neowise/login.php?token=${token}`;

    emailjs.send("service_dn2sl3i", "template_f9ffhs9", {
        email: userEmail,
        reset_link: resetLink
    })
    .then(() => {
        mostrarToast("E-mail de recuperação enviado com sucesso!");
        modal.style.display = "none";
    })
    .catch(() => {
        mostrarToast("Erro ao enviar o e-mail. Tente novamente.");
    });
});

function mostrarToast(msg) {
    toast.innerText = msg;
    toast.classList.add("show");
    setTimeout(() => {
        toast.classList.remove("show");
    }, 3000);
}
