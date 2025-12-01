-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Tempo de geração: 01/12/2025 às 00:26
-- Versão do servidor: 10.4.32-MariaDB
-- Versão do PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Banco de dados: `db_finwise`
--

-- --------------------------------------------------------

--
-- Estrutura para tabela `alunos`
--

CREATE TABLE `alunos` (
  `CPF` varchar(12) NOT NULL,
  `Nome` varchar(100) NOT NULL,
  `Email` varchar(150) NOT NULL,
  `Telefone` int(12) NOT NULL,
  `Senhahash` varchar(255) NOT NULL,
  `acesso` varchar(250) DEFAULT NULL,
  `Foto` varchar(250) DEFAULT NULL,
  `data_cadastro` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `alunos`
--

INSERT INTO `alunos` (`CPF`, `Nome`, `Email`, `Telefone`, `Senhahash`, `acesso`, `Foto`, `data_cadastro`) VALUES
('09876543210', 'Carlos Henrique Moraes Dos Santos', 'carlossantos.gp54@gmail.com', 2147483647, '$2y$10$U/dMIVgmMRMKEBTwKyjc6.T.dMPMHlKGA8/DU9bv7RYa9g2VDg1ka', '0', NULL, NULL),
('11122233344', 'Ana Silva', 'ana.silva@email.com', 2147483647, '$2y$10$U/dMIVgmMRMKEBTwKyjc6.T.dMPMHlKGA8/DU9bv7RYa9g2VDg1ka', '0', NULL, '2025-11-29 21:34:02'),
('12212121', 'Jotinha', 'carlos@gmail.com', 1212121212, '$2y$10$U/dMIVgmMRMKEBTwKyjc6.T.dMPMHlKGA8/DU9bv7RYa9g2VDg1ka', '1', NULL, NULL),
('12345678901', 'Adm', 'ADM@gmail.com', 2147483647, '$2y$10$U/dMIVgmMRMKEBTwKyjc6.T.dMPMHlKGA8/DU9bv7RYa9g2VDg1ka', '1', NULL, NULL),
('32456899657', 'carlinha', 'carlos@gmail.com', 2147483647, '$2y$10$U/dMIVgmMRMKEBTwKyjc6.T.dMPMHlKGA8/DU9bv7RYa9g2VDg1ka', '0', NULL, NULL),
('55566677788', 'Bruno Costa', 'bruno.costa@email.com', 2147483647, '$2y$10$U/dMIVgmMRMKEBTwKyjc6.T.dMPMHlKGA8/DU9bv7RYa9g2VDg1ka', '0', NULL, '2025-11-29 21:34:02'),
('77775677877', 'carlinha', 'pogger@emeilrola.com', 2147483647, '$2y$10$bu7LfIEim954tOi/nTCgGuHCjNdVjsxi.iJZHwJH3OVRrY5mh0VT.', '0', NULL, NULL),
('99988877766', 'Carla Dias', 'carla.dias@email.com', 2147483647, '$2y$10$U/dMIVgmMRMKEBTwKyjc6.T.dMPMHlKGA8/DU9bv7RYa9g2VDg1ka', '0', NULL, '2025-11-29 21:34:02');

-- --------------------------------------------------------

--
-- Estrutura para tabela `aluno_cursos`
--

CREATE TABLE `aluno_cursos` (
  `id_curso_aluno` int(11) NOT NULL,
  `cpf_aluno` varchar(12) DEFAULT NULL,
  `id_curso` int(11) DEFAULT NULL,
  `progresso` varchar(250) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `aluno_cursos`
--

INSERT INTO `aluno_cursos` (`id_curso_aluno`, `cpf_aluno`, `id_curso`, `progresso`) VALUES
(6, '12345678901', 22, '0'),
(7, '09876543210', 22, '100'),
(8, '55566677788', 31, '0');

-- --------------------------------------------------------

--
-- Estrutura para tabela `cursos`
--

CREATE TABLE `cursos` (
  `Id_curso` int(11) NOT NULL,
  `Categoria` varchar(100) DEFAULT NULL,
  `Nome` varchar(100) DEFAULT NULL,
  `Descrição` varchar(650) DEFAULT NULL,
  `Conteudo` varchar(650) DEFAULT NULL,
  `Atividades` varchar(650) DEFAULT NULL,
  `Beneficios` varchar(650) DEFAULT NULL,
  `Preço` varchar(120) DEFAULT NULL,
  `Preçoparce` varchar(120) DEFAULT NULL,
  `imagem` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `cursos`
--

INSERT INTO `cursos` (`Id_curso`, `Categoria`, `Nome`, `Descrição`, `Conteudo`, `Atividades`, `Beneficios`, `Preço`, `Preçoparce`, `imagem`) VALUES
(22, 'Programaçao', 'Back-End Completo', 'Aprenda a desenvolver aplicações completas no lado do servidor usando as principais tecnologias do mercado. Ideal para quem busca entrar na área de TI com foco sólido em lógica e estrutura de sistemas.', 'Lógica de programação aplicada ao back-end,  APIs REST, Banco de dados SQL e NoSQL  Autenticação e segurança, Deploy e boas práticas', 'Projetos práticos orientados, Construção de uma API completa, Exercícios de banco de dados, Desafios semanais', 'Portfólio profissional, Certificado reconhecido, Preparação para vagas júnior, Suporte de instrutores', '497', '80', 'backend completa.png'),
(23, 'Investimentos', 'Criptomoedas e Blockchain', 'Entenda de forma simples como funcionam criptomoedas, blockchain e tecnologias descentralizadas. Ideal para iniciantes que querem investir ou conhecer o mercado.', 'O que é blockchain  Como funcionam criptos  Exchanges e carteiras  Mineração e staking  Segurança e golpes comuns', 'Simulações de compra e venda  Análise prática de gráficos  Configuração de carteira digital', 'Base sólida para investimentos  Entendimento real da tecnologia  Maior segurança ao operar no mercado', '280', '46', 'criptomoedas_e_blockchain.png'),
(24, 'Finanças', 'Introdução a Finanças Pessoais', 'Organize seu orçamento, aprenda a economizar e comece a investir. O primeiro passo para sua independência financeira.', 'Orçamento doméstico, Controle de gastos, Renda fixa vs. variável, Como sair das dívidas, Objetivos financeiros.', 'Planilha de orçamento, Simulação de investimentos, Estudo de caso de dívidas.', 'Controle financeiro total, Plano de economia, Base para investir com segurança.', '197', '30', 'curso-financas.png'),
(25, 'Programação', 'Front-End Completo', 'Crie interfaces web modernas e responsivas. Domine HTML, CSS e JavaScript, as três pilares do desenvolvimento front-end.', 'HTML5 semântico, CSS3 (Flexbox, Grid), JavaScript moderno (ES6+), Responsividade, Conceitos de Frameworks.', 'Criação de um portfólio web, Clone de interface (ex: Netflix), Projeto de e-commerce responsivo.', 'Construir sites do zero, Portfólio prático, Alta demanda no mercado de TI.', '450', '75', 'curso-frontend.png'),
(26, 'Investimentos', 'Investimentos para Iniciantes', 'Perca o medo de investir. Aprenda os conceitos básicos para fazer seu dinheiro render mais que a poupança com segurança.', 'O que é inflação, Renda Fixa (Tesouro Direto, CDB), Renda Variável (Ações, FIIs), Perfil de investidor, Corretoras.', 'Abertura de conta em corretora (simulado), Montagem de carteira básica, Análise de perfil de risco.', 'Começar a investir com confiança, Diversificação de carteira, Entendimento dos principais ativos.', '250', '40', 'curso-investimentos.png'),
(27, 'Marketing', 'Marketing Digital', 'Aprenda a criar estratégias de marketing digital do zero. De SEO e Mídias Sociais a E-mail Marketing e Ads.', 'SEO On-Page/Off-Page, Google Ads, Facebook/Instagram Ads, E-mail marketing, Inbound Marketing.', 'Criação de campanha de Ads, Análise de métricas (Google Analytics), Planejamento de conteúdo.', 'Gerar tráfego qualificado, Aumentar vendas online, Certificado para gestão de mídias.', '380', '65', 'curso-marketing.png'),
(28, 'Investimentos', 'Mercado de Ações', 'Mergulhe na análise fundamentalista e técnica para tomar decisões de investimento em ações de forma profissional.', 'Análise fundamentalista (valuation), Análise técnica (gráficos), Swing trade vs. Buy and hold, Setores da bolsa, Gerenciamento de risco.', 'Análise de balanços (case real), Operações em simulador (Home Broker), Estratégias de dividendos.', 'Seleção autônoma de ações, Estratégias de rentabilidade, Visão profissional do mercado.', '550', '90', 'curso-mercado.png'),
(29, 'Finanças', 'Planejamento Financeiro Avançado', 'Para quem já investe e quer otimizar a carteira, planejamento sucessório e estratégias de alocação de ativos complexos.', 'Alocação de ativos (Asset Allocation), Investimentos no exterior, Planejamento sucessório (Holding, Previdência), Otimização tributária.', 'Estudo de caso (grande patrimônio), Análise de carteira internacional, Estruturação de previdência privada.', 'Gestão de patrimônio eficiente, Proteção de capital, Visão de longo prazo e sucessão.', '650', '110', 'curso-planejamento.png'),
(30, 'Negócios/Tecnologia', 'Gestão de Projetos Ágeis (Scrum)', 'Aprenda o framework Scrum para gerenciar projetos de forma flexível e eficiente, entregando valor rapidamente.', 'Introdução ao Ágil, Papéis do Scrum (Scrum Master, Product Owner, Time de Desenvolvimento), Eventos do Scrum (Sprint, Daily Scrum), Artefatos.', 'Simulação de um Sprint, Criação de Backlog, Estudo de caso de adoção Ágil.', 'Melhoria na produtividade, Certificado de conclusão, Preparação para certificações Scrum.', '490', '80', 'gestao_de_projetos.png'),
(31, 'Finanças/Tecnologia', 'Excel para Finanças e Análise de Dados', 'Domine as funções avançadas do Excel mais usadas no mercado financeiro para modelagem e análise de dados.', 'Fórmulas Financeiras (VP, VF, PGTO), Tabelas Dinâmicas, Funções Condicionais (SE, SOMASE), Gráficos avançados, Introdução ao VBA.', 'Criação de fluxo de caixa, Modelagem de orçamento, Análise de DRE (Demonstração do Resultado do Exercício).', 'Agilidade na análise, Melhores decisões financeiras, Habilidade essencial no currículo.', '290', '50', 'excel_para_financas.png'),
(32, 'Tecnologia', 'Cibersegurança e Proteção de Dados Pessoais', 'Entenda os principais riscos digitais e as melhores práticas para proteger seus dados e os de sua empresa.', 'Ataques Comuns (Phishing, Malware), VPNs e Firewalls, Criptografia, Segurança de Senhas, Lei Geral de Proteção de Dados (LGPD).', 'Configuração de VPN (simulada), Análise de cabeçalhos de email (Phishing), Plano de resposta a incidentes.', 'Maior segurança digital, Conhecimento em LGPD, Proteção contra fraudes.', '350', '60', 'cibersegurança_e_protecao.png'),
(33, 'Finanças', 'Contabilidade para Não Contadores', 'Entenda os conceitos básicos da contabilidade para analisar a saúde financeira de empresas e tomar melhores decisões de investimento.', 'Balancete, DRE, Balanço Patrimonial, Ativo, Passivo e Patrimônio Líquido, Análise de Indicadores (Margem, Endividamento).', 'Análise de Balanço simplificado, Interpretação de Indicadores, Estudo de caso de empresa listada.', 'Visão empresarial, Análise de investimento mais completa, Linguagem de negócios.', '220', '35', 'contabilidade_para_nao.png');

-- --------------------------------------------------------

--
-- Estrutura para tabela `forum`
--

CREATE TABLE `forum` (
  `idmensagem` int(11) NOT NULL,
  `cpfmensagem` varchar(11) DEFAULT NULL,
  `mensagem` varchar(250) DEFAULT NULL,
  `hr` datetime DEFAULT current_timestamp(),
  `topico` varchar(250) DEFAULT NULL,
  `likes` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `forum`
--

INSERT INTO `forum` (`idmensagem`, `cpfmensagem`, `mensagem`, `hr`, `topico`, `likes`) VALUES
(7, '77775677877', 'Devia ter uma Lei ! ', '2025-11-28 09:12:28', 'Mercado de Ações', 1),
(8, '11122233344', 'Estou começando no curso de Front-End. Qual a diferença real entre Flexbox e Grid? Quando usar cada um?', '2025-11-29 21:34:02', 'Programação', 0),
(9, '99988877766', 'O curso de Finanças Pessoais é muito bom, mas qual o primeiro passo DEPOIS de organizar o orçamento? Quitar dívidas ou começar a investir (mesmo que pouco)?', '2025-11-29 21:34:02', 'Finanças Pessoais', 2);

-- --------------------------------------------------------

--
-- Estrutura para tabela `forum_comentarios`
--

CREATE TABLE `forum_comentarios` (
  `idmensagem` int(11) DEFAULT NULL,
  `id_comentario` int(11) NOT NULL,
  `comentario` varchar(250) DEFAULT NULL,
  `hr` datetime DEFAULT current_timestamp(),
  `cpfusuario` varchar(12) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `forum_comentarios`
--

INSERT INTO `forum_comentarios` (`idmensagem`, `id_comentario`, `comentario`, `hr`, `cpfusuario`) VALUES
(8, 3, 'Ótima pergunta! Eu uso Flexbox para alinhar itens em uma dimensão (linha ou coluna) e Grid para layout em duas dimensões. Grid é mais poderoso para o layout geral da página.', '2025-11-29 21:34:02', '55566677788'),
(9, 4, 'Depende da dívida. Se for juros altos (cartão de crédito), quite AGORA. Se for juros baixos (financiamento imobiliário), talvez valha a pena investir ao mesmo tempo.', '2025-11-29 21:34:02', '77775677877'),
(9, 5, 'Concordo com a Carlinha. Pague os juros caros primeiro. É o \"investimento\" com maior retorno garantido.', '2025-11-29 21:34:02', '11122233344');

-- --------------------------------------------------------

--
-- Estrutura para tabela `mensagem`
--

CREATE TABLE `mensagem` (
  `id_mensagem` int(11) NOT NULL,
  `nome_mensagem` varchar(100) DEFAULT NULL,
  `mensagem_texto` text DEFAULT NULL,
  `assunto_mensagem` varchar(100) DEFAULT NULL,
  `email_mensagem` varchar(100) DEFAULT NULL,
  `Respondido` varchar(10) DEFAULT 'NÃO',
  `respostas` varchar(400) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `mensagem`
--

INSERT INTO `mensagem` (`id_mensagem`, `nome_mensagem`, `mensagem_texto`, `assunto_mensagem`, `email_mensagem`, `Respondido`, `respostas`) VALUES
(2, 'carlinha', 'gggg', 'ggg', 'pogger@emeilrola.com', 'SIM', 'aaaa');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos`
--

CREATE TABLE `pagamentos` (
  `id_compra` int(11) NOT NULL,
  `cpf` varchar(12) DEFAULT NULL,
  `hr` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pagamentos`
--

INSERT INTO `pagamentos` (`id_compra`, `cpf`, `hr`) VALUES
(4, NULL, '2025-11-29 14:20:19'),
(5, NULL, '2025-11-29 14:20:19'),
(6, NULL, '2025-11-29 14:20:19'),
(7, '12345678901', '2025-11-30 22:49:46'),
(8, '09876543210', '2025-11-30 22:55:38'),
(9, '55566677788', '2025-11-30 23:39:32');

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos_boleto`
--

CREATE TABLE `pagamentos_boleto` (
  `id_cursos` int(11) DEFAULT NULL,
  `valor` decimal(10,2) DEFAULT NULL,
  `cpf` varchar(12) DEFAULT NULL,
  `itens` int(11) DEFAULT NULL,
  `codigo` varchar(44) DEFAULT NULL,
  `vencimento` date DEFAULT NULL,
  `hr` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos_cartao`
--

CREATE TABLE `pagamentos_cartao` (
  `id_pagamento` int(11) NOT NULL,
  `valor` decimal(10,2) NOT NULL,
  `cpf` varchar(12) NOT NULL,
  `itens` int(11) DEFAULT NULL,
  `CVV` varchar(20) DEFAULT NULL,
  `Validade` varchar(10) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Estrutura para tabela `pagamentos_pix`
--

CREATE TABLE `pagamentos_pix` (
  `id_pagamento` int(11) DEFAULT NULL,
  `hr` datetime DEFAULT current_timestamp(),
  `valor` int(11) DEFAULT NULL,
  `cpf` varchar(12) DEFAULT NULL,
  `itens` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `pagamentos_pix`
--

INSERT INTO `pagamentos_pix` (`id_pagamento`, `hr`, `valor`, `cpf`, `itens`) VALUES
(7, '2025-11-30 22:49:46', 72, '12345678901', 22),
(8, '2025-11-30 22:55:38', 72, '09876543210', 22),
(9, '2025-11-30 23:39:32', 45, '55566677788', 31);

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas`
--

CREATE TABLE `provas` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `titulo` varchar(255) NOT NULL,
  `descricao` text DEFAULT NULL,
  `qtd_questoes` int(11) DEFAULT 10,
  `nota_minima` int(11) DEFAULT 7,
  `ativa` tinyint(1) DEFAULT 1,
  `criada_em` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `provas`
--

INSERT INTO `provas` (`id`, `id_curso`, `titulo`, `descricao`, `qtd_questoes`, `nota_minima`, `ativa`, `criada_em`) VALUES
(2, 22, 'Prova Final: Back-End Completo', 'Teste seus conhecimentos em Back-End.', 10, 7, 1, '2025-11-30 00:34:02'),
(3, 23, 'Certificação: Criptomoedas e Blockchain', 'Teste seus conhecimentos em Cripto.', 10, 7, 1, '2025-11-30 00:34:02'),
(4, 24, 'Teste: Introdução a Finanças Pessoais', 'Teste seus conhecimentos em Finanças Pessoais.', 10, 7, 1, '2025-11-30 00:34:02'),
(5, 25, 'Prova Final: Front-End Essencial', 'Teste seus conhecimentos em Front-End.', 10, 7, 1, '2025-11-30 00:34:02'),
(6, 26, 'Teste: Investimentos para Iniciantes', 'Teste seus conhecimentos em Investimentos.', 10, 7, 1, '2025-11-30 00:34:02'),
(7, 27, 'Certificação: Marketing Digital Completo', 'Teste seus conhecimentos em Marketing Digital.', 10, 7, 1, '2025-11-30 00:34:02'),
(8, 28, 'Prova Final: Mercado de Ações (Avançado)', 'Teste seus conhecimentos em Mercado de Ações.', 10, 7, 1, '2025-11-30 00:34:02'),
(9, 29, 'Certificação: Planejamento Financeiro Avançado', 'Teste seus conhecimentos em Planejamento Avançado.', 10, 7, 1, '2025-11-30 00:34:02'),
(10, 30, 'Certificação: Gestão de Projetos Ágeis', 'Teste seus conhecimentos em Scrum e metodologias ágeis.', 10, 7, 1, '2025-11-30 00:54:03'),
(11, 31, 'Prova Final: Excel para Finanças', 'Teste suas habilidades em Excel e modelagem financeira.', 10, 7, 1, '2025-11-30 00:54:03'),
(12, 32, 'Teste: Cibersegurança e LGPD', 'Teste seus conhecimentos em proteção de dados e segurança digital.', 10, 7, 1, '2025-11-30 00:54:03'),
(13, 33, 'Certificação: Contabilidade Básica', 'Teste sua compreensão dos conceitos contábeis fundamentais.', 10, 7, 1, '2025-11-30 00:54:03');

-- --------------------------------------------------------

--
-- Estrutura para tabela `provas_aluno`
--

CREATE TABLE `provas_aluno` (
  `id_prova` int(11) DEFAULT NULL,
  `cpf_aluno` varchar(12) DEFAULT NULL,
  `id_aluno_prova` int(11) NOT NULL,
  `feito` varchar(250) DEFAULT 'Não',
  `nota` int(11) DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `provas_aluno`
--

INSERT INTO `provas_aluno` (`id_prova`, `cpf_aluno`, `id_aluno_prova`, `feito`, `nota`) VALUES
(2, '09876543210', 3, 'sim', 10);

-- --------------------------------------------------------

--
-- Estrutura para tabela `questoes`
--

CREATE TABLE `questoes` (
  `id` int(11) NOT NULL,
  `id_curso` int(11) NOT NULL,
  `enunciado` text NOT NULL,
  `alternativa_a` varchar(255) NOT NULL,
  `alternativa_b` varchar(255) NOT NULL,
  `alternativa_c` varchar(255) NOT NULL,
  `alternativa_d` varchar(255) NOT NULL,
  `correta` enum('A','B','C','D') NOT NULL,
  `id_prova` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Despejando dados para a tabela `questoes`
--

INSERT INTO `questoes` (`id`, `id_curso`, `enunciado`, `alternativa_a`, `alternativa_b`, `alternativa_c`, `alternativa_d`, `correta`, `id_prova`) VALUES
(3, 22, 'O que é uma API REST?', 'Um banco de dados', 'Um protocolo de front-end', 'Um padrão de arquitetura para comunicação entre sistemas', 'Uma linguagem de programação', 'C', 2),
(4, 22, 'Qual comando SQL é usado para consultar dados?', 'INSERT', 'SELECT', 'UPDATE', 'DELETE', 'B', 2),
(5, 23, 'O que é Blockchain?', 'Um tipo de criptomoeda', 'Um banco de dados centralizado', 'Um livro-razão distribuído e imutável', 'Uma carteira digital', 'C', 3),
(6, 23, 'O que é \"Staking\"?', 'Minerar com hardware', 'Guardar criptos para validar transações e ganhar recompensas', 'Vender na alta e comprar na baixa', 'Um tipo de golpe', 'B', 3),
(7, 24, 'Qual o primeiro passo para organizar as finanças?', 'Investir em ações', 'Fazer um orçamento (mapear gastos e receitas)', 'Pedir um empréstimo', 'Comprar criptomoedas', 'B', 4),
(8, 24, 'O que é \"Reserva de Emergência\"?', 'Dinheiro para comprar na promoção', 'Dinheiro para investir em alto risco', 'Um valor para cobrir imprevistos (ex: 6 meses de custo de vida)', 'A poupança', 'C', 4),
(9, 25, 'Qual a função principal do HTML?', 'Estilizar a página', 'Estruturar o conteúdo (semântica)', 'Adicionar interatividade (programação)', 'Gerenciar o banco de dados', 'B', 5),
(10, 25, 'Qual propriedade CSS é usada para criar layouts 2D (linhas e colunas)?', 'flexbox', 'position: absolute', 'display: grid', 'font-family', 'C', 5),
(11, 26, 'Qual é um exemplo de Renda Fixa?', 'Ações da Petrobras', 'Tesouro Selic (Tesouro Direto)', 'Bitcoin', 'Fundos Imobiliários (FIIs)', 'B', 6),
(12, 26, 'O que significa \"diversificação\"?', 'Investir todo o dinheiro em uma só ação', 'Não investir', 'Distribuir o investimento em diferentes ativos para reduzir o risco', 'Investir apenas na poupança', 'C', 6),
(13, 27, 'O que é SEO?', 'Comprar anúncios no Google', 'Gerenciar redes sociais', 'Otimizar um site para aparecer bem nos resultados orgânicos (não pagos) do Google', 'Enviar e-mail marketing', 'C', 7),
(14, 27, '\"Tráfego Pago\" se refere a:', 'Visitas orgânicas do Google', 'Visitas vindas de anúncios (Google Ads, Facebook Ads)', 'Visitas de redes sociais', 'Visitas por e-mail', 'B', 7),
(15, 28, 'Análise Fundamentalista foca em:', 'Gráficos de preço e volume', 'Notícias de curto prazo', 'Saúde financeira da empresa (lucro, dívida, etc.) para longo prazo', 'Sentimento do mercado', 'C', 8),
(16, 28, 'O que é \"Buy and Hold\"?', 'Comprar e vender ações no mesmo dia', 'Vender ações que você não possui', 'Uma estratégia de comprar ações e mantê-las por muitos anos (longo prazo)', 'Análise de gráficos', 'C', 8),
(17, 29, 'O que é \"Asset Allocation\"?', 'Escolher a melhor ação do momento', 'A estratégia de distribuir o patrimônio entre diferentes classes de ativos (ações, renda fixa, exterior)', 'Investir apenas em imóveis', 'Um tipo de previdência privada', 'B', 9),
(18, 29, 'Qual o objetivo principal do planejamento sucessório?', 'Pagar menos impostos hoje', 'Garantir a transferência eficiente do patrimônio aos herdeiros (evitando inventário)', 'Dobrar o patrimônio em 1 ano', 'Investir em criptomoedas', 'B', 9),
(19, 22, 'Qual destes é um banco de dados NoSQL?', 'MySQL', 'PostgreSQL', 'MongoDB', 'SQLite', 'C', 2),
(20, 22, 'O que significa \"Deploy\"?', 'Testar o código', 'Escrever o código', 'Publicar a aplicação em um servidor', 'Corrigir bugs', 'C', 2),
(21, 22, 'Qual é a principal função do Node.js?', 'Estilizar páginas web', 'Executar JavaScript no lado do servidor', 'Gerenciar bancos de dados SQL', 'Criar apps mobile', 'B', 2),
(22, 22, 'O que é \"Autenticação\"?', 'Definir o que um usuário pode fazer', 'Verificar quem o usuário é', 'Otimizar o banco de dados', 'Conectar ao front-end', 'B', 2),
(23, 22, 'O que é \"Autorização\"?', 'Verificar quem o usuário é', 'Definir o que um usuário autenticado pode fazer', 'Criar um token JWT', 'Criptografar a senha', 'B', 2),
(24, 22, 'HTTP é um protocolo...', 'Com estado (Stateful)', 'Sem estado (Stateless)', 'Exclusivo para back-end', 'De banco de dados', 'B', 2),
(25, 22, 'O que é um ORM (Object-Relational Mapper)?', 'Um tipo de API', 'Uma ferramenta que traduz código SQL para objetos', 'Uma biblioteca de front-end', 'Um servidor web', 'B', 2),
(26, 22, 'Qual método HTTP é usado para ATUALIZAR um recurso existente?', 'GET', 'POST', 'DELETE', 'PUT', 'D', 2),
(27, 23, 'Quem criou o Bitcoin?', 'Vitalik Buterin', 'Elon Musk', 'Satoshi Nakamoto', 'Binance', 'C', 3),
(28, 23, 'O que é \"Mineração\" de criptomoedas?', 'Comprar moedas na baixa', 'Guardar moedas em uma carteira fria', 'Usar poder computacional para validar transações e criar novas moedas', 'Um tipo de exchange', 'C', 3),
(29, 23, 'Qual a principal característica do Ethereum (além de ser uma moeda)?', 'Privacidade total', 'Transações instantâneas', 'Permitir a execução de Contratos Inteligentes (Smart Contracts)', 'Ser lastreado em dólar', 'C', 3),
(30, 23, 'O que é uma \"Wallet Fria\" (Cold Wallet)?', 'Uma carteira online', 'Uma conta em uma exchange', 'Uma carteira de hardware (offline) para máxima segurança', 'Uma carteira com poucas moedas', 'C', 3),
(31, 23, 'O que é \"DeFi\"?', 'Finanças Definidas', 'Dinheiro Fiduciário', 'Finanças Descentralizadas (serviços financeiros em blockchain)', 'Um tipo de mineração', 'C', 3),
(32, 23, 'O que é uma \"Stablecoin\"?', 'Uma moeda que não varia de preço', 'Uma moeda lastreada em outra (ex: Dólar)', 'Uma moeda que só pode ser usada em um país', 'Uma moeda que não existe mais', 'B', 3),
(33, 23, 'O que é \"KYC\" (Know Your Customer) em exchanges?', 'Uma estratégia de trade', 'O processo de verificar a identidade do usuário', 'Um bônus de boas-vindas', 'Um tipo de carteira', 'B', 3),
(34, 23, 'O que é \"Halving\" do Bitcoin?', 'A divisão da rede em duas', 'Um evento que corta a recompensa de mineração pela metade', 'O processo de vender metade das moedas', 'A taxa de transação', 'B', 3),
(35, 24, 'Qual destes NÃO é considerado um \"gasto fixo\"?', 'Aluguel', 'Conta de luz', 'Mensalidade escolar', 'Jantar em restaurante', 'D', 4),
(36, 24, 'O que são \"gastos variáveis\"?', 'Gastos que nunca mudam', 'Gastos que você não pode cortar', 'Gastos que mudam de valor todo mês (supermercado, lazer)', 'Investimentos', 'C', 4),
(37, 24, 'Qual o principal risco de usar o \"cheque especial\"?', 'É muito burocrático', 'Os juros são extremamente altos', 'O banco pode cancelar a qualquer momento', 'Não pode ser usado para compras online', 'B', 4),
(38, 24, 'O que é \"Renda Ativa\"?', 'Renda de investimentos', 'Renda que exige seu trabalho/tempo (salário)', 'Renda de aluguéis', 'Dinheiro da poupança', 'B', 4),
(39, 24, 'O que é \"Renda Passiva\"?', 'Salário mensal', 'Dinheiro ganho sem esforço contínuo (dividendos, aluguéis)', 'Horas extras', 'Dinheiro do cheque especial', 'B', 4),
(40, 24, 'Por que a inflação é ruim para o dinheiro guardado?', 'Porque ela aumenta o valor do dinheiro', 'Porque ela diminui o poder de compra do dinheiro', 'Porque ela bloqueia o dinheiro no banco', 'Porque ela gera impostos', 'B', 4),
(41, 24, 'Qual a melhor forma de sair de dívidas caras (cartão de crédito)?', 'Investir em ações', 'Ignorar a dívida', 'Tentar renegociar por juros menores ou pagar o máximo possível', 'Fazer outra dívida para pagar a primeira', 'C', 4),
(42, 24, 'O que significa \"ter um objetivo financeiro\"?', 'Gastar todo o salário', 'Definir uma meta clara para o seu dinheiro (comprar casa, aposentadoria)', 'Guardar dinheiro na poupança sem motivo', 'Investir em alto risco', 'B', 4),
(43, 25, 'Qual tag HTML é usada para criar um link?', '< a >', '< link >', '< href >', '< p >', 'A', 5),
(44, 25, 'O que o CSS (Cascading Style Sheets) faz?', 'Controla a estrutura da página', 'Controla a apresentação visual (cores, fontes, layout)', 'Controla a lógica e interatividade', 'Controla o servidor', 'B', 5),
(45, 25, 'O que é \"Design Responsivo\"?', 'Um design bonito', 'Um design que se adapta a diferentes tamanhos de tela (desktop, celular)', 'Um design que responde rápido', 'Um design feito em CSS Grid', 'B', 5),
(46, 25, 'Qual a principal função do JavaScript no Front-End?', 'Armazenar dados', 'Adicionar interatividade e lógica à página', 'Definir a estrutura', 'Definir as cores', 'B', 5),
(47, 25, 'O que é \"Git\"?', 'Uma linguagem de programação', 'Um framework JavaScript', 'Um sistema de controle de versão (para salvar histórico do código)', 'Um editor de texto', 'C', 5),
(48, 25, 'O que é o \"DOM\" (Document Object Model)?', 'O design da página', 'A representação do HTML como uma árvore de objetos que o JavaScript pode manipular', 'O arquivo CSS', 'O servidor onde o site está hospedado', 'B', 5),
(49, 25, 'O que é \"npm\" (Node Package Manager)?', 'Um editor de código', 'Um gerenciador de pacotes/bibliotecas JavaScript', 'Um tipo de HTML', 'Um framework CSS', 'B', 5),
(50, 25, 'O que faz a propriedade CSS `display: flex;`?', 'Esconde o elemento', 'Transforma o elemento em um container flexível (para alinhar itens)', 'Muda a fonte do texto', 'Adiciona uma borda', 'B', 5),
(51, 26, 'Qual a principal vantagem da Renda Fixa?', 'Alta rentabilidade', 'Previsibilidade e segurança', 'Rápido crescimento', 'Isenção total de impostos', 'B', 6),
(52, 26, 'O que é a Taxa SELIC?', 'A taxa de inflação', 'A taxa básica de juros da economia brasileira', 'O rendimento da bolsa de valores', 'Um imposto sobre investimentos', 'B', 6),
(53, 26, 'O que é um Fundo de Investimento?', 'Uma única ação', 'Um \"condomínio\" de investidores que juntam dinheiro para um gestor aplicar', 'Investimento no exterior', 'Apenas Renda Fixa', 'B', 6),
(54, 26, 'O que são \"Dividendos\"?', 'Taxas cobradas pela corretora', 'Parte do lucro de uma empresa distribuída aos acionistas', 'Juros da Renda Fixa', 'O preço de compra da ação', 'B', 6),
(55, 26, 'O que é o FGC (Fundo Garantidor de Créditos)?', 'Garante o lucro do investimento', 'Garante investimentos em ações', 'Protege o investidor (até certo limite) em caso de quebra do banco (ex: CDB, Poupança)', 'Um tipo de fundo de ações', 'C', 6),
(56, 26, 'Qual o investimento considerado o mais seguro do Brasil?', 'Ações da Vale', 'Bitcoin', 'Tesouro Direto (títulos do governo)', 'Fundos Imobiliários', 'C', 6),
(57, 26, 'Qual destes é um exemplo de Renda Variável?', 'Poupança', 'CDB do Banco X', 'Tesouro IPCA+', 'Ações da Magazine Luiza', 'D', 6),
(58, 26, 'O que define um \"Perfil de Investidor Conservador\"?', 'Busca alto risco e alto retorno', 'Busca equilibrar risco e retorno', 'Busca segurança, mesmo que o retorno seja menor', 'Investe apenas em criptomoedas', 'C', 6),
(59, 27, 'O que é \"Inbound Marketing\"?', 'Marketing de interrupção (anúncios de TV)', 'Marketing de atração (criar conteúdo para o cliente vir até você)', 'Venda porta a porta', 'Telemarketing', 'B', 7),
(60, 27, 'O que é um \"Lead\" no Marketing Digital?', 'Um clique no anúncio', 'Um visitante do site', 'Um potencial cliente que forneceu informações de contato (ex: email)', 'Um cliente que já comprou', 'C', 7),
(61, 27, 'O que é \"Taxa de Conversão\"?', 'O custo do anúncio', 'O número de visitantes do site', 'A porcentagem de visitantes que realiza uma ação desejada (ex: compra, cadastro)', 'O número de seguidores', 'C', 7),
(62, 27, 'O que é \"Google Analytics\"?', 'Ferramenta para criar anúncios', 'Ferramenta para analisar o tráfego e comportamento dos usuários no site', 'Ferramenta de email marketing', 'Rede social', 'B', 7),
(63, 27, 'Qual o objetivo do E-mail Marketing?', 'Enviar spam', 'Construir relacionamento com leads e clientes, e gerar vendas', 'Apenas vender', 'Otimizar o site para o Google', 'B', 7),
(64, 27, 'O que é \"CAC\" (Custo de Aquisição de Cliente)?', 'O lucro total do cliente', 'Quanto a empresa gasta para conquistar um novo cliente', 'O valor total das vendas', 'A taxa de cliques', 'B', 7),
(65, 27, 'O que é \"LTV\" (Lifetime Value)?', 'O custo do cliente', 'O valor total (lucro/receita) que um cliente gera para a empresa ao longo do tempo', 'O primeiro valor de compra', 'O custo do anúncio', 'B', 7),
(66, 27, 'Qual a diferença entre SEO e SEM?', 'SEO é pago, SEM é orgânico', 'SEO é orgânico (gratuito), SEM inclui SEO e mídia paga (anúncios)', 'SEO é para redes sociais, SEM é para Google', 'Não há diferença', 'B', 7),
(67, 28, 'O que é \"Valuation\"?', 'A análise de gráficos', 'O processo de estimar o valor justo de uma empresa/ação', 'A diversificação da carteira', 'A compra de ações no exterior', 'B', 8),
(68, 28, 'Análise Técnica (Gráfica) foca em:', 'Lucros e dívidas da empresa', 'Padrões de preço e volume para prever movimentos de curto prazo', 'Notícias macroeconômicas', 'Dividendos', 'B', 8),
(69, 28, 'O que é \"Ibovespa\"?', 'Uma ação específica', 'O principal índice da bolsa de valores brasileira (uma carteira teórica)', 'A taxa de juros', 'A corretora de valores do governo', 'B', 8),
(70, 28, 'O que é um \"Home Broker\"?', 'O analista que recomenda ações', 'A sede da bolsa de valores', 'A plataforma online (software) onde se compra e vende ações', 'Um fundo de investimento', 'C', 8),
(71, 28, 'O que é \"Stop Loss\"?', 'Uma ordem para parar de investir', 'Uma ordem automática para vender uma ação se ela atingir certo preço de queda (para limitar perdas)', 'O lucro máximo da operação', 'A taxa da corretora', 'B', 8),
(72, 28, 'O que são \"Small Caps\"?', 'Ações de empresas grandes e consolidadas', 'Ações de empresas com menor valor de mercado (potencialmente maior risco/retorno)', 'Ações que pagam altos dividendos', 'Ações estrangeiras', 'B', 8),
(73, 28, 'O que são \"Blue Chips\"?', 'Ações de empresas pequenas', 'Ações de empresas grandes, consolidadas e com alta liquidez (ex: Vale, Petrobras)', 'Criptomoedas', 'Títulos de renda fixa', 'B', 8),
(74, 28, 'O que é \"Liquidez\" de uma ação?', 'O lucro que ela dá', 'A facilidade de comprar ou vender essa ação rapidamente sem afetar seu preço', 'O risco de queda', 'O setor da empresa', 'B', 8),
(75, 29, 'O que é um \"ETF\" (Exchange Traded Fund)?', 'Uma ação individual', 'Um fundo de investimento negociado na bolsa como se fosse uma ação (ex: BOVA11)', 'Um título de renda fixa', 'Uma holding familiar', 'B', 9),
(76, 29, 'Investir no exterior (ex: Ações nos EUA) serve para:', 'Apenas especulação', 'Diversificação geográfica e exposição a moedas fortes (dólar)', 'Pagar menos impostos no Brasil', 'Ter maior segurança que o Tesouro Direto', 'B', 9),
(77, 29, 'O que é \"Previdência Privada\" (ex: PGBL, VGBL)?', 'Um investimento focado na aposentadoria, com benefícios fiscais', 'Um substituto do FGC', 'Um tipo de ação', 'Um investimento de curto prazo', 'A', 9),
(78, 29, 'Qual a principal diferença entre PGBL e VGBL?', 'PGBL é para curto prazo, VGBL para longo prazo', 'PGBL permite abatimento no Imposto de Renda (declaração completa), VGBL não', 'PGBL é Renda Variável, VGBL é Renda Fixa', 'PGBL não tem imposto', 'B', 9),
(79, 29, 'O que é \"Otimização Tributária\" em investimentos?', 'Não pagar impostos (sonegação)', 'Escolher investimentos e estratégias para pagar menos impostos de forma legal', 'Investir apenas na poupança', 'Declarar o imposto de renda', 'B', 9),
(80, 29, 'O que é uma \"Holding Familiar\"?', 'Uma conta conjunta', 'Uma empresa criada para administrar o patrimônio da família (facilita a sucessão)', 'Um tipo de fundo de investimento', 'Uma corretora de valores', 'B', 9),
(81, 29, 'O que são \"Investimentos Alternativos\"?', 'Poupança e CDB', 'Ações e Fundos Imobiliários', 'Investimentos fora do mercado tradicional (ex: Cripto, Private Equity, Arte)', 'Apenas Tesouro Direto', 'C', 9),
(82, 29, 'O que é \"Risco de Longevidade\" no planejamento financeiro?', 'O risco de morrer cedo', 'O risco de viver muito tempo e o dinheiro acabar antes', 'O risco da inflação', 'O risco do mercado de ações', 'B', 9),
(83, 30, 'Qual o objetivo principal do \"Daily Scrum\"?', 'Revisar todo o projeto', 'Sincronizar o time e inspecionar o progresso em relação à meta da Sprint', 'Definir o que será feito na próxima Sprint', 'Apresentar o produto ao cliente', 'B', 10),
(84, 30, 'Quem é o responsável por maximizar o valor do trabalho do Time de Desenvolvimento?', 'Scrum Master', 'Stakeholder', 'Product Owner', 'Gerente de Projetos', 'C', 10),
(85, 30, 'O que é um \"Sprint Backlog\"?', 'Uma lista de todas as tarefas do projeto', 'O objetivo da Sprint + a seleção de itens do Product Backlog + o plano para entregar o Incremento', 'Uma reunião de 8 horas', 'O contrato com o cliente', 'B', 10),
(86, 30, 'Qual o período de tempo máximo recomendado para uma Sprint?', '6 meses', '1 ano', '4 semanas (1 mês)', '1 semana', 'C', 10),
(87, 30, 'O que é \"Burndown Chart\"?', 'Um gráfico que mede a satisfação do cliente', 'Um gráfico que mostra o trabalho restante em uma Sprint', 'Um tipo de reunião ágil', 'Um artefato do Kanban', 'B', 10),
(88, 30, 'Qual o principal foco da Retrospectiva da Sprint?', 'Avaliar se o produto atende ao cliente', 'Revisar o Incremento (o que foi construído)', 'Inspecionar como a Sprint foi conduzida e planejar melhorias no processo', 'Negociar o orçamento', 'C', 10),
(89, 30, 'Qual o nome do documento que lista e prioriza as funcionalidades do produto?', 'Plano de Marketing', 'Product Backlog', 'Manual do Usuário', 'Cronograma', 'B', 10),
(90, 30, 'O que é o \"Incremento\" (ou Pedaço de Produto)?', 'O valor final do projeto', 'A soma de todos os itens do Product Backlog completados durante a Sprint e Sprints anteriores', 'Um aumento no custo do projeto', 'O plano de testes', 'B', 10),
(91, 30, 'Quem é o responsável por remover impedimentos do Time de Desenvolvimento?', 'Product Owner', 'Cliente', 'Scrum Master', 'Qualquer membro do time', 'C', 10),
(92, 30, 'Qual o pilar do Scrum que se refere à transparência nos processos e artefatos?', 'Inspeção', 'Adaptação', 'Comprometimento', 'Transparência', 'D', 10),
(93, 31, 'Qual função do Excel é usada para calcular o valor presente de uma série de pagamentos futuros?', '=VF()', '=VP()', '=PGTO()', '=TAXA()', 'B', 11),
(94, 31, 'Qual a principal finalidade de uma \"Tabela Dinâmica\" (Pivot Table)?', 'Criar fórmulas complexas', 'Armazenar dados em uma base de dados externa', 'Resumir, analisar, explorar e apresentar dados de forma flexível', 'Criar macros em VBA', 'C', 11),
(95, 31, 'Para somar apenas os valores em um intervalo que atendem a uma condição específica, você deve usar a função:', '=SOMA.SE()', '=MÉDIA.SE()', '=PROCV()', '=ÍNDICE()', 'A', 11),
(96, 31, 'A sigla \"SE\" na função SE(teste_lógico; valor_se_verdadeiro; valor_se_falso) significa:', 'Salário de Entrada', 'Somatório Efetivo', 'Somente Este', 'Se (condicional)', 'D', 11),
(97, 31, 'Qual o atalho de teclado mais comum para inserir a data e hora atual no Excel?', 'Ctrl + Shift + ;', 'Ctrl + Alt + Del', 'Ctrl + C', 'F2', 'A', 11),
(98, 31, 'Qual função é ideal para buscar um valor em uma coluna e retornar o valor correspondente em outra coluna na mesma linha?', '=SE()', '=SOMA()', '=PROCV()', '=MÁXIMO()', 'C', 11),
(99, 31, 'A função \"=PGTO(taxa; nper; vp)\" calcula:', 'O valor final do investimento', 'O valor presente do investimento', 'O pagamento periódico de um empréstimo ou investimento', 'A taxa de juros real', 'C', 11),
(100, 31, 'No Excel, o que as células A1:$B$10 representam?', 'Uma referência relativa', 'Uma referência mista', 'Uma referência absoluta', 'Uma referência a uma célula', 'C', 11),
(101, 31, 'Como você calcula a variação percentual entre o valor da célula B2 e B1?', '=(B2-B1)/B1', '=(B2+B1)/B1', '=B2/B1', '=B2-B1', 'A', 11),
(102, 31, 'O que é a \"Validação de Dados\" no Excel?', 'Uma fórmula complexa', 'Uma ferramenta que permite controlar o tipo de dado que pode ser inserido em uma célula', 'Uma macro que calcula valores', 'Uma forma de criptografar a planilha', 'B', 11),
(103, 32, 'O que é \"Phishing\"?', 'Um tipo de vírus que se espalha rapidamente', 'Uma tentativa de obter informações confidenciais (ex: senhas) fingindo ser uma entidade confiável', 'Um programa que otimiza o computador', 'Uma rede social de segurança', 'B', 12),
(104, 32, 'Qual a função principal de uma VPN (Rede Virtual Privada)?', 'Aumentar a velocidade da internet', 'Tornar a conexão de internet privada e criptografada (protegendo o tráfego)', 'Bloquear anúncios pop-up', 'Acessar o banco de dados do governo', 'B', 12),
(105, 32, 'O que é um \"Firewall\"?', 'Um programa que criptografa arquivos', 'Um sistema de segurança que monitora e controla o tráfego de rede de entrada e saída', 'Um tipo de ataque hacker', 'Uma ferramenta de recuperação de senha', 'B', 12),
(106, 32, 'A Lei Geral de Proteção de Dados (LGPD) no Brasil regulamenta:', 'Apenas dados de empresas públicas', 'O uso e tratamento de dados pessoais por pessoas físicas e jurídicas', 'A segurança de senhas de redes sociais', 'O comércio eletrônico', 'B', 12),
(107, 32, 'O que significa \"Autenticação de Dois Fatores\" (2FA)?', 'Usar duas senhas diferentes', 'Um método de segurança que exige duas formas diferentes de verificação de identidade', 'Conectar-se por VPN e Firewall', 'Ter dois antivírus instalados', 'B', 12),
(108, 32, 'O que é \"Malware\"?', 'Um tipo de software de segurança', 'Software malicioso projetado para causar dano, roubar dados ou obter acesso não autorizado', 'Uma rede de computadores interligados', 'Um tipo de criptomoeda', 'B', 12),
(109, 32, 'O que é \"Criptografia\"?', 'Tornar dados ilegíveis para quem não possui a chave de decodificação', 'Um método de duplicação de arquivos', 'Um tipo de vírus', 'O processo de deletar dados permanentemente', 'A', 12),
(110, 32, 'Qual destes é o tipo de senha mais seguro?', 'Sua data de nascimento', 'Uma palavra comum e curta', 'Uma combinação longa e aleatória de letras, números e símbolos', 'Uma sequência numérica (ex: 123456)', 'C', 12),
(111, 32, 'O que é um \"Backup\" (Cópia de Segurança)?', 'Acesso não autorizado a um sistema', 'Cópia de arquivos e dados em um local separado para recuperação em caso de perda', 'Um tipo de ataque DDoS', 'O processo de instalação de um sistema operacional', 'B', 12),
(112, 32, 'A \"engenharia social\" em segurança se baseia principalmente em:', 'Explorar falhas de software', 'Explorar a vulnerabilidade humana (manipulação, confiança) para obter informações', 'Usar força bruta para adivinhar senhas', 'Atacar a rede com vírus', 'B', 12),
(113, 33, 'A equação fundamental da Contabilidade é:', 'Ativo = Passivo + Receita', 'Ativo = Passivo + Patrimônio Líquido', 'Ativo = Despesa + Lucro', 'Receita = Custos + Despesas', 'B', 13),
(114, 33, 'O que é \"Ativo\" no Balanço Patrimonial?', 'As obrigações da empresa', 'Os bens e direitos da empresa (o que ela possui)', 'O valor que os sócios investiram', 'O lucro do período', 'B', 13),
(115, 33, 'O que é \"Passivo\" no Balanço Patrimonial?', 'Os bens da empresa', 'As obrigações da empresa com terceiros (dívidas)', 'O lucro retido', 'As contas a receber', 'B', 13),
(116, 33, 'O que é o \"Patrimônio Líquido\"?', 'O valor total das dívidas', 'O Ativo menos o Passivo (a riqueza líquida dos sócios)', 'A soma das receitas e despesas', 'O capital de terceiros', 'B', 13),
(117, 33, 'O que o \"DRE\" (Demonstração do Resultado do Exercício) demonstra?', 'A posição financeira da empresa em um momento específico', 'O lucro ou prejuízo da empresa em um período de tempo', 'O fluxo de caixa da empresa', 'O valor do estoque', 'B', 13),
(118, 33, 'Qual destes é um exemplo de \"Despesa Operacional\"?', 'Compra de matéria-prima', 'Salário da equipe administrativa', 'Venda de mercadoria', 'Empréstimo bancário', 'B', 13),
(119, 33, 'O que representa o indicador de \"Margem Líquida\"?', 'O quanto do preço de venda se transforma em lucro líquido', 'O quanto a empresa deve a terceiros', 'A capacidade de a empresa pagar suas contas de curto prazo', 'O retorno sobre o investimento', 'A', 13),
(120, 33, 'O que são \"Receitas\" em Contabilidade?', 'Dinheiro gasto para produzir bens ou serviços', 'Entradas de dinheiro provenientes da atividade principal da empresa (venda de bens/serviços)', 'Dívidas da empresa', 'Impostos pagos', 'B', 13),
(121, 33, 'O que significa um \"Ativo Circulante\" maior que o \"Passivo Circulante\"?', 'A empresa não tem dívidas', 'A empresa tem capacidade de pagar suas dívidas de curto prazo com seus bens de curto prazo', 'A empresa está em crise', 'A empresa tem um grande estoque', 'B', 13),
(122, 33, 'Qual é a finalidade principal do \"Balanço Patrimonial\"?', 'Calcular o Imposto de Renda', 'Fornecer uma visão estática da situação financeira e patrimonial da empresa em uma data específica', 'Mostrar o custo da mercadoria vendida', 'Analisar o mercado externo', 'B', 13);

-- --------------------------------------------------------

--
-- Estrutura para tabela `respostas`
--

CREATE TABLE `respostas` (
  `Id_curso_aluno` varchar(12) DEFAULT NULL,
  `id_prova` int(11) DEFAULT NULL,
  `id_questao` int(11) DEFAULT NULL,
  `alternativa` varchar(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Índices para tabelas despejadas
--

--
-- Índices de tabela `alunos`
--
ALTER TABLE `alunos`
  ADD PRIMARY KEY (`CPF`);

--
-- Índices de tabela `aluno_cursos`
--
ALTER TABLE `aluno_cursos`
  ADD PRIMARY KEY (`id_curso_aluno`),
  ADD KEY `cpf_aluno` (`cpf_aluno`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Índices de tabela `cursos`
--
ALTER TABLE `cursos`
  ADD PRIMARY KEY (`Id_curso`);

--
-- Índices de tabela `forum`
--
ALTER TABLE `forum`
  ADD PRIMARY KEY (`idmensagem`),
  ADD KEY `cpfmensagem` (`cpfmensagem`);

--
-- Índices de tabela `forum_comentarios`
--
ALTER TABLE `forum_comentarios`
  ADD PRIMARY KEY (`id_comentario`),
  ADD KEY `idmensagem` (`idmensagem`),
  ADD KEY `fk_comentario_aluno` (`cpfusuario`);

--
-- Índices de tabela `mensagem`
--
ALTER TABLE `mensagem`
  ADD PRIMARY KEY (`id_mensagem`);

--
-- Índices de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD PRIMARY KEY (`id_compra`),
  ADD KEY `cpf` (`cpf`);

--
-- Índices de tabela `pagamentos_boleto`
--
ALTER TABLE `pagamentos_boleto`
  ADD KEY `cpf` (`cpf`),
  ADD KEY `id_cursos` (`id_cursos`),
  ADD KEY `itens` (`itens`);

--
-- Índices de tabela `pagamentos_cartao`
--
ALTER TABLE `pagamentos_cartao`
  ADD KEY `cpf` (`cpf`),
  ADD KEY `id_pagamento` (`id_pagamento`),
  ADD KEY `fk_cartao_curso_itens` (`itens`);

--
-- Índices de tabela `pagamentos_pix`
--
ALTER TABLE `pagamentos_pix`
  ADD KEY `id_pagamento` (`id_pagamento`),
  ADD KEY `cpf` (`cpf`),
  ADD KEY `fk_pagamentopix_itens` (`itens`);

--
-- Índices de tabela `provas`
--
ALTER TABLE `provas`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_curso` (`id_curso`);

--
-- Índices de tabela `provas_aluno`
--
ALTER TABLE `provas_aluno`
  ADD PRIMARY KEY (`id_aluno_prova`),
  ADD KEY `id_prova` (`id_prova`),
  ADD KEY `cpf_aluno` (`cpf_aluno`);

--
-- Índices de tabela `questoes`
--
ALTER TABLE `questoes`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_curso` (`id_curso`),
  ADD KEY `id_prova` (`id_prova`);

--
-- Índices de tabela `respostas`
--
ALTER TABLE `respostas`
  ADD UNIQUE KEY `CPF` (`Id_curso_aluno`),
  ADD KEY `id_prova` (`id_prova`),
  ADD KEY `id_questao` (`id_questao`);

--
-- AUTO_INCREMENT para tabelas despejadas
--

--
-- AUTO_INCREMENT de tabela `aluno_cursos`
--
ALTER TABLE `aluno_cursos`
  MODIFY `id_curso_aluno` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT de tabela `cursos`
--
ALTER TABLE `cursos`
  MODIFY `Id_curso` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=34;

--
-- AUTO_INCREMENT de tabela `forum`
--
ALTER TABLE `forum`
  MODIFY `idmensagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `forum_comentarios`
--
ALTER TABLE `forum_comentarios`
  MODIFY `id_comentario` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT de tabela `mensagem`
--
ALTER TABLE `mensagem`
  MODIFY `id_mensagem` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT de tabela `pagamentos`
--
ALTER TABLE `pagamentos`
  MODIFY `id_compra` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT de tabela `provas`
--
ALTER TABLE `provas`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT de tabela `provas_aluno`
--
ALTER TABLE `provas_aluno`
  MODIFY `id_aluno_prova` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT de tabela `questoes`
--
ALTER TABLE `questoes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=123;

--
-- Restrições para tabelas despejadas
--

--
-- Restrições para tabelas `aluno_cursos`
--
ALTER TABLE `aluno_cursos`
  ADD CONSTRAINT `aluno_cursos_ibfk_1` FOREIGN KEY (`cpf_aluno`) REFERENCES `alunos` (`CPF`),
  ADD CONSTRAINT `aluno_cursos_ibfk_2` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`Id_curso`);

--
-- Restrições para tabelas `forum`
--
ALTER TABLE `forum`
  ADD CONSTRAINT `forum_ibfk_1` FOREIGN KEY (`cpfmensagem`) REFERENCES `alunos` (`CPF`);

--
-- Restrições para tabelas `forum_comentarios`
--
ALTER TABLE `forum_comentarios`
  ADD CONSTRAINT `fk_comentario_aluno` FOREIGN KEY (`cpfusuario`) REFERENCES `alunos` (`CPF`),
  ADD CONSTRAINT `forum_comentarios_ibfk_1` FOREIGN KEY (`idmensagem`) REFERENCES `forum` (`idmensagem`);

--
-- Restrições para tabelas `pagamentos`
--
ALTER TABLE `pagamentos`
  ADD CONSTRAINT `cpf` FOREIGN KEY (`cpf`) REFERENCES `alunos` (`CPF`);

--
-- Restrições para tabelas `pagamentos_boleto`
--
ALTER TABLE `pagamentos_boleto`
  ADD CONSTRAINT `pagamentos_boleto_ibfk_1` FOREIGN KEY (`cpf`) REFERENCES `alunos` (`CPF`),
  ADD CONSTRAINT `pagamentos_boleto_ibfk_2` FOREIGN KEY (`id_cursos`) REFERENCES `cursos` (`Id_curso`),
  ADD CONSTRAINT `pagamentos_boleto_ibfk_3` FOREIGN KEY (`itens`) REFERENCES `cursos` (`Id_curso`);

--
-- Restrições para tabelas `pagamentos_cartao`
--
ALTER TABLE `pagamentos_cartao`
  ADD CONSTRAINT `fk_cartao_curso_itens` FOREIGN KEY (`itens`) REFERENCES `cursos` (`Id_curso`),
  ADD CONSTRAINT `pagamentos_cartao_ibfk_1` FOREIGN KEY (`cpf`) REFERENCES `alunos` (`CPF`),
  ADD CONSTRAINT `pagamentos_cartao_ibfk_2` FOREIGN KEY (`id_pagamento`) REFERENCES `pagamentos` (`id_compra`);

--
-- Restrições para tabelas `pagamentos_pix`
--
ALTER TABLE `pagamentos_pix`
  ADD CONSTRAINT `fk_pagamentopix_itens` FOREIGN KEY (`itens`) REFERENCES `cursos` (`Id_curso`),
  ADD CONSTRAINT `pagamentos_pix_ibfk_2` FOREIGN KEY (`cpf`) REFERENCES `alunos` (`CPF`);

--
-- Restrições para tabelas `provas`
--
ALTER TABLE `provas`
  ADD CONSTRAINT `provas_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`Id_curso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `provas_aluno`
--
ALTER TABLE `provas_aluno`
  ADD CONSTRAINT `provas_aluno_ibfk_1` FOREIGN KEY (`id_prova`) REFERENCES `provas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `provas_aluno_ibfk_2` FOREIGN KEY (`cpf_aluno`) REFERENCES `alunos` (`CPF`);

--
-- Restrições para tabelas `questoes`
--
ALTER TABLE `questoes`
  ADD CONSTRAINT `id_prova` FOREIGN KEY (`id_prova`) REFERENCES `provas` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `questoes_ibfk_1` FOREIGN KEY (`id_curso`) REFERENCES `cursos` (`Id_curso`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Restrições para tabelas `respostas`
--
ALTER TABLE `respostas`
  ADD CONSTRAINT `respostas_ibfk_1` FOREIGN KEY (`Id_curso_aluno`) REFERENCES `alunos` (`CPF`),
  ADD CONSTRAINT `respostas_ibfk_2` FOREIGN KEY (`id_prova`) REFERENCES `provas` (`id`),
  ADD CONSTRAINT `respostas_ibfk_3` FOREIGN KEY (`id_questao`) REFERENCES `questoes` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
