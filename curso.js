// Função auxiliar para preencher listas (pode permanecer a mesma)
const preencherLista = (id, itens) => {
    const ul = document.getElementById(id);
    if (!ul || !itens || !Array.isArray(itens)) return; // Adicionado verificação de array
    
    ul.innerHTML = "";
    itens.forEach(item => {
        const li = document.createElement("li");
        li.textContent = item;
        ul.appendChild(li);
    });
};

const params = new URLSearchParams(window.location.search);
const cursoId = params.get("id");

const id = parseInt(cursoId); // O ID do curso que vamos buscar no PHP

// Função principal para carregar os dados
async function carregarDetalhesCurso(id) {
    // 1. Mostrar estado de carregamento inicial
    document.getElementById("curso-titulo").innerText = "Carregando...";
    document.getElementById("curso-descricao").innerText = "";
    
    // Configuração para o caso de erro
    const exibirErro = (mensagem) => {
        document.getElementById("curso-titulo").innerText = "Curso não encontrado";
        document.getElementById("curso-descricao").innerText = mensagem;
        document.getElementById("curso-preco").innerText = "-";
        document.getElementById("curso-preco-parcelado").innerText = "-";
        // Limpar listas, se necessário
        preencherLista("curso-conteudo", []);
        preencherLista("curso-atividades", []);
        preencherLista("curso-beneficios", []);
    };
    
    if (!id) {
        exibirErro("Nenhum ID de curso fornecido na URL.");
        return;
    }

    try {
        // 2. Fazer a requisição ao PHP
        const resposta = await fetch(`buscar_curso.php?id=${id}`);
        
        // 3. Processar a resposta
        if (resposta.ok) { // Status 200 (Sucesso)
            const curso = await resposta.json(); // Transforma o JSON do PHP no objeto 'curso'
            
            // Verifica se o objeto foi retornado corretamente
            if (curso && curso.titulo) {
                // 4. PREENCHER OS ELEMENTOS DA PÁGINA (Lógica idêntica ao seu código original)
                
                document.getElementById("curso-titulo").innerText = curso.titulo;
                document.getElementById("curso-descricao").innerText = curso.descricao;

                const img = document.getElementById("curso-imagem");
                if (img) {
                    img.src = "Mídias/" + curso.imagem;
                    img.alt = "Imagem do " + curso.titulo;
                }

                // Chamar a função preencherLista com os dados do PHP
                preencherLista("curso-conteudo", curso.conteudo);
                preencherLista("curso-atividades", curso.atividades);
                preencherLista("curso-beneficios", curso.beneficios);

                document.getElementById("curso-preco").innerText = curso.preco;
                document.getElementById("curso-preco-parcelado").innerText = curso.precoParcelado;

                document.getElementById("pagamento").href = "pagamento.php?id=" + id; // Usa o ID da URL

            } else {
                exibirErro("Erro: Os dados do curso vieram incompletos do servidor.");
            }
            
        } else { // Status 400, 404, 500
            let erroData = {};
            try {
                erroData = await resposta.json();
            } catch (e) {
                // Não é JSON
            }
            const mensagem = erroData.error || `Erro ao carregar curso. Status: ${resposta.status}.`;
            exibirErro(mensagem);
        }
        
    } catch (erro) {
        // 5. Tratar erro de rede
        console.error("Erro na comunicação com o servidor:", erro);
        exibirErro("Erro de conexão com o servidor. Verifique sua rede.");
    }
}

// Executar a função quando o script for carregado
carregarDetalhesCurso(cursoId);