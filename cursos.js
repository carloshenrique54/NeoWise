// Seletores do DOM
const container = document.getElementById("cursos-lista");
const prevBtn = document.getElementById('prev');
const nextBtn = document.getElementById('next');
const paginaSpan = document.getElementById('pagina-atual');
const input = document.getElementById('search');
const select = document.getElementById('curso-select');

// Configuração da página
const linhasPorPagina = 2;
const colunasPorPagina = 3;
const cursosPorPagina = linhasPorPagina * colunasPorPagina;
let paginaAtual = 1;

// Cursos recebidos do PHP
let cursosFiltrados = [...cursos]; 

// Criação dos cards
function criarCards(cursosArray) {
    container.innerHTML = '';
    cursosArray.forEach(curso => {
        const img = curso.Imagem ?? 'default.png';
        const card = document.createElement("div");
        card.classList.add("curso");
        card.setAttribute("data-categoria", curso.Categoria);
        card.innerHTML = `
            <img src="Mídias/Cursos/${img}" alt="${curso.Nome}" />
            <h3>${curso.Nome}</h3>
            <p>${curso.Descricao ?? 'Descrição não disponível.'}</p>
            <a href="curso.php?id=${curso.Id_curso}" class="acessar-btn">Acessar Curso</a>
        `;
        container.appendChild(card);
    });
}

// Normalização para pesquisa
const normalizar = str => str ? str.normalize("NFD").replace(/\p{Diacritic}/gu, "").toLowerCase() : '';

// Função de filtro
function filtrarCursos() {
    const termo = normalizar(input.value);
    const categoria = select.value;

    cursosFiltrados = cursos.filter(curso => {
        const titulo = normalizar(curso.Nome);
        const catCurso = curso.Categoria;
        return (titulo.includes(termo) || termo === '') && (categoria === '' || categoria === catCurso);
    });

    paginaAtual = 1;
    mostrarPagina();
}

// Paginação
function mostrarPagina() {
    const totalPaginas = Math.ceil(cursosFiltrados.length / cursosPorPagina) || 1;
    if (paginaAtual > totalPaginas) paginaAtual = totalPaginas;
    if (paginaAtual < 1) paginaAtual = 1;

    const start = (paginaAtual - 1) * cursosPorPagina;
    const end = start + cursosPorPagina;

    criarCards(cursosFiltrados.slice(start, end));
    paginaSpan.textContent = `${paginaAtual} / ${totalPaginas}`;

    // Habilita/desabilita botões
    prevBtn.disabled = paginaAtual <= 1;
    nextBtn.disabled = paginaAtual >= totalPaginas;
}

// Eventos
input.addEventListener('input', filtrarCursos);
select.addEventListener('change', filtrarCursos);

prevBtn.addEventListener('click', () => {
    paginaAtual--;
    mostrarPagina();
});

nextBtn.addEventListener('click', () => {
    paginaAtual++;
    mostrarPagina();
});


filtrarCursos();