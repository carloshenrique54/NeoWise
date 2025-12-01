document.addEventListener('DOMContentLoaded', function() {
    // 1. SELETORES DOS ELEMENTOS
    const botaoFotoPerfil = document.getElementById('botao-foto-perfil');
    const inputFotoPerfil = document.getElementById('upload-foto-perfil');
    const imgFotoPerfil = document.querySelector('.perfil-foto');
    
    // VARIÁVEIS DE AJAX
    const scriptUpload = 'upload_foto.php'; // Nome do seu script PHP de upload

    // ==========================================================
    // PARTE 1: GARANTIR O CLIQUE NO BOTÃO
    // ==========================================================
    if (!botaoFotoPerfil || !inputFotoPerfil || !imgFotoPerfil) {
        console.error("ERRO JS: Elementos críticos (botão, input ou imagem) da foto de perfil não encontrados. Verifique IDs e classes.");
        return; // Interrompe se os elementos críticos não existirem
    }

    botaoFotoPerfil.addEventListener('click', function() {
        // Esta linha abre a caixa de diálogo de arquivo
        inputFotoPerfil.click(); 
    });

    // ==========================================================
    // PARTE 2: LÓGICA DE PRÉ-VISUALIZAÇÃO E ENVIO AJAX
    // ==========================================================
    inputFotoPerfil.addEventListener('change', function() {
        if (this.files && this.files[0]) {
            const arquivo = this.files[0];
            
            // --- PRÉ-VISUALIZAÇÃO IMEDIATA (CLIENTE) ---
            const reader = new FileReader();
            reader.onload = function(e) {
                // Atualiza o src com a pré-visualização do arquivo local
                imgFotoPerfil.src = e.target.result;
            };
            reader.readAsDataURL(arquivo);


            // --- ENVIO AJAX PARA O SERVIDOR (USANDO fetch CORRETAMENTE) ---
            const formData = new FormData();
            formData.append('nova_foto', arquivo); // 'nova_foto' é o nome que o PHP espera

            fetch(scriptUpload, {
                method: 'POST',
                body: formData
            })
            .then(response => {
                if (!response.ok) {
                    // Lança um erro se o status HTTP não for 200 (ex: 404, 500)
                    throw new Error(`Erro na rede ou no servidor. Status: ${response.status}`);
                }
                // PASSO CORRETO: Pega a resposta e tenta decodificar como JSON.
                // Se o PHP retornou {"sucesso":true,...} isso funcionará perfeitamente.
                return response.json(); 
            })
            .then(data => {
                // SÓ É EXECUTADO SE O JSON FOR VÁLIDO E A REQUISIÇÃO FOR OK (status 200)
                if (data.sucesso) {
                    // Sucesso no PHP.
                    alert('Foto de perfil atualizada com sucesso!');
                    
                    // Atualiza a imagem com a URL final do servidor (garantia)
                    if (data.nova_url) {
                        // Adicionamos um timestamp para forçar o navegador a recarregar a nova imagem (cache busting)
                        imgFotoPerfil.src = data.nova_url + '?' + new Date().getTime(); 
                    }
                } else {
                    // Erro retornado pelo PHP (ex: "tamanho inválido")
                    alert('Falha no upload: ' + (data.erro || 'Erro desconhecido retornado pelo servidor.'));
                    
                    // Opcional: Se falhar, você pode querer reverter a foto de perfil para a anterior aqui.
                }
            })
            .catch(error => {
                // Captura: erro de rede, JSON inválido ou qualquer erro lançado por 'throw new Error'
                console.error('Erro de requisição AJAX (Verifique o PHP):', error);
                alert('Ocorreu um erro na comunicação. Por favor, tente novamente ou verifique o console para detalhes.');
            });
        }
    });

});