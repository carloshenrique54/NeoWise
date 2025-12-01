  (function(){
    emailjs.init("yexqvxDUHrrXPU6Wf"); 
  })();

  const form = document.getElementById("reset-form");
  const toast = document.getElementById('toast');

  form.addEventListener("submit", function(e) {
    e.preventDefault();
    
    const userEmail = document.getElementById("email").value;

    // Aqui você geraria um token ou link único de reset
    // Exemplo simplificado:
    const resetLink = "file:///D:/Projeto%20-%20FinWise/esqueci_senha.html" + Date.now();

    // Envia via EmailJS
    emailjs.send("service_dn2sl3i", "template_f9ffhs9", {
      email: userEmail,
      reset_link: resetLink
    })
    .then(() => {
      toast.innerText = "E-mail de recuperação enviado com sucesso!";
      toast.classList.add('show');
      setTimeout(() => {
      toast.classList.remove('show');
      }, 3000);
}
    , (error) => {
      toast.innerText = "Erro ao enviar o e-mail. Tente novamente.";
      toast.classList.add('show');
      setTimeout(() => {
      toast.classList.remove('show');
      }, 3000);
    });
  });


  //Abrir e fechar modal

  const forgotPasswordBtn = document.getElementById("forgot-password");
  const modal = document.getElementById("modal-reset");
  const closeModalBtn = document.getElementById("close-modal");

  forgotPasswordBtn.addEventListener("click", function() {
    modal.style.display = "flex";
  });

  closeModalBtn.addEventListener("click", function() {
    modal.style.display = "none";
  });