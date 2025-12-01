# NeoWise

NeoWise é uma plataforma web de venda e gerenciamento de cursos online, construída com PHP, MySQL, HTML, CSS e JavaScript. Permite que administradores publiquem cursos e que alunos se cadastrem, comprem cursos, façam provas e participem de fóruns.

---

## Visão Geral

O NeoWise funciona como uma plataforma de cursos online (LMS básico), centralizando:

- Gestão de usuários
- Publicação e compra de cursos
- Emissão de certificados
- Sistema de provas
- Fórum de discussão

---

## Funcionalidades

- Cadastro, login e autenticação de usuários
- Painel administrativo para gerenciar cursos, usuários e provas
- Página de cursos com compra/inscrição
- Sistema de pagamento simulado
- Emissão de certificados em PDF
- Sistema de provas integrado aos cursos
- Fórum de discussão para interação entre alunos
- Upload de foto de perfil
- Dashboard para acompanhamento de cursos e progresso

---

/ — raiz do projeto
index.php — Página inicial / login
login.php, loging.php — Autenticação
logout.php — Logout
cadastro.php + .js/.css — Cadastro de usuários
cursos.php + .js/.css — Listagem e compra de cursos
criarprova.php, prova.php, responder.php — Sistema de provas
forum.php, forum_add.php — Fórum de discussão
perfil.php, perfiladm.php, foto-perfil.php, upload_foto.php — Perfis e upload de imagem
contato.php + .js/.css — Formulário de contato
db_finwise.sql — Banco de dados


---

## Tecnologias Utilizadas

- PHP (back-end)
- MySQL / MariaDB (banco de dados)
- HTML, CSS e JavaScript (front-end)
- FPDF (geração de certificados em PDF)

---

## Instalação / Configuração

1. Clone o repositório:

```bash
git clone https://github.com/carloshenrique54/NeoWise.git

## Estrutura de Arquivos

