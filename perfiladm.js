const select = document.getElementById("filtroTempo");
const ganhosEl = document.getElementById("ganhos");
const usuariosEl = document.getElementById("usuarios");

function atualizarDashboard(filtro = "mes") {
  fetch(`dados_dashboard.php?filtro=${filtro}`)
    .then(res => res.json())
    .then(data => {
      ganhosEl.textContent = data.ganhos || 0;
      usuariosEl.textContent = data.usuarios || 0;
    })
    .catch(err => console.error("Erro ao carregar dados:", err));
}

select.addEventListener("change", () => {
  atualizarDashboard(select.value);
});

atualizarDashboard();