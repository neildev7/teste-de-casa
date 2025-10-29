<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Last SENAI | O Chamado do Herói</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Press+Start+2P&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --bg-dark: #1a1c2c;
            --ui-main: #5a3a2b;
            --ui-border-light: #a18c7c;
            --ui-border-dark: #3f2a1f;
            --text-light: #ffffff;
            --text-highlight: #ffc800;
            --cursor-pointer: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 8 8"><path fill="%23ffc800" d="M0 0v8l4-4-4-4z"/></svg>') 8 8, auto;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Press Start 2P', cursive;
            background: url("{{ asset('img/giphy.gif') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-color: var(--bg-dark);
            background-blend-mode: multiply;
            color: var(--text-light);
            image-rendering: pixelated;
        }

        .overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.75); z-index: -1; }
        
        .dialog-box {
            position: relative; z-index: 1;
            padding: 25px;
            max-width: 800px;
            width: 100%;
            background: var(--ui-main);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light), 0 10px 30px rgba(0,0,0,0.5);
            opacity: 0;
            animation: fadeIn 0.5s 0.2s forwards;
            min-height: 400px;
            display: flex;
            flex-direction: column;
            /* Melhoria: A caixa inteira é clicável */
            cursor: var(--cursor-pointer);
            outline: none; /* Foco customizado abaixo */
        }
        /* Melhoria A11y: Foco para navegação via Tab */
        .dialog-box:focus-visible {
            border-color: var(--text-highlight);
            box-shadow: inset 0 0 0 4px var(--ui-border-light), 0 0 0 4px var(--text-highlight);
        }

        .dialog-header {
            display: flex;
            align-items: center;
            gap: 20px;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 4px solid var(--ui-border-dark);
        }
        
        .npc-avatar {
            width: 80px;
            height: 80px;
            border: 4px solid var(--ui-border-light);
            object-fit: cover;
            background: var(--bg-dark);
            flex-shrink: 0;
        }
        
        .dialog-title {
            font-size: 1.2rem;
            color: var(--text-highlight);
            text-shadow: 2px 2px #000;
            text-align: left;
        }
        
        .dialog-content {
            font-size: 1rem;
            line-height: 1.8;
            text-align: left;
            flex-grow: 1;
            /* Garante que o texto quebre corretamente */
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .dialog-content strong {
            color: var(--text-highlight);
        }

        .next-indicator {
            position: absolute;
            bottom: 25px; /* Alinhado com o botão de pular */
            right: 25px;
            background: none;
            border: none;
            color: var(--text-light);
            font-size: 1rem;
            text-transform: uppercase;
            text-shadow: 2px 2px #000;
            animation: blink 1.5s infinite steps(1);
            text-decoration: none;
            font-family: 'Press Start 2P', cursive;
            /* Não é mais um botão, apenas um indicador visual */
            pointer-events: none; 
        }
        .next-indicator::after {
            content: ' ▼';
            color: var(--text-highlight);
        }
        
        /* Novo: Botão "Pular Tutorial" */
        .skip-button {
            position: absolute;
            bottom: 25px;
            left: 25px;
            font-size: 0.8rem;
            color: var(--ui-border-light);
            text-decoration: underline;
            font-family: 'Press Start 2P', cursive;
            cursor: var(--cursor-pointer);
            transition: color 0.2s;
        }
        .skip-button:hover, .skip-button:focus-visible {
            color: var(--text-light);
            outline: none;
        }
        
        @keyframes fadeIn { to { opacity: 1; } }
        @keyframes blink {
            50% { opacity: 0.5; }
        }
    </style>
</head>
<body>

<div class="overlay"></div>

<main class="dialog-box" 
      id="dialogBox" 
      tabindex="0" 
      data-next-url="{{ route('character.allocate', $character->id) }}">
    
    <header class="dialog-header">
        <img src="{{ asset('img/avatar-4.png') }}" class="npc-avatar" alt="Mestre do Jogo">
        <h1 class="dialog-title">Mestre Ramon</h1>
    </header>
    
    <div class="dialog-content" id="dialogContent">
        </div>

    <a href="{{ route('character.allocate', $character->id) }}" class="skip-button" id="skipButton">Pular Tutorial</a>

    <div id="nextIndicator" class="next-indicator" style="display: none;">
        CONTINUAR
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const app = {
        ui: {
            dialogBox: document.getElementById('dialogBox'),
            content: document.getElementById('dialogContent'),
            nextIndicator: document.getElementById('nextIndicator'),
            skipButton: document.getElementById('skipButton'),
        },
        state: {
            pages: [
                `Saudações, {{ $character->name }}! O destino de Pixelândia repousa sobre seus ombros. A jornada será árdua, mas sua lenda começa agora...`,
                `O 'Grande Reset' falhou, deixando um Eco Sombrio para trás. Criaturas corrompidas agora vagam pela terra. Sua missão é purificá-las.`,
                `Para sobreviver, você deve dominar seus atributos: HP para resistir, MP para conjurar magias e ATAQUE para destruir seus inimigos.`,
                `Prepare-se. Na próxima tela, você irá forjar seu poder distribuindo seus pontos de atributo. Escolha com sabedoria. Boa sorte, herói!`
            ],
            currentPage: 0,
            isTyping: false,
            timeoutID: null,
            nextUrl: '',
        },

        init() {
            // Foca a caixa de diálogo para que o 'Enter' funcione imediatamente
            this.ui.dialogBox.focus(); 
            this.state.nextUrl = this.ui.dialogBox.dataset.nextUrl;
            this.bindEvents();
            this.showPage(0);
        },

        bindEvents() {
            // Novo: Clicar na caixa (ou pressionar Enter) avança o diálogo
            this.ui.dialogBox.addEventListener('click', (e) => {
                // Impede que o clique no botão "Pular" também avance o diálogo
                if (e.target.closest('#skipButton')) {
                    return;
                }
                this.handleAdvance();
            });

            // Novo: Pressionar Enter avança o diálogo
            this.ui.dialogBox.addEventListener('keydown', (e) => {
                if (e.key === 'Enter') {
                    e.preventDefault(); // Impede o 'Enter' de "clicar" em links
                    this.handleAdvance();
                }
            });
        },

        /**
         * Lógica principal: ou pula a digitação, ou avança a página.
         */
        handleAdvance() {
            if (this.state.isTyping) {
                this.skipTyping();
            } else {
                this.nextPage();
            }
        },

        /**
         * Pula a animação de digitação e mostra o texto completo.
         */
        skipTyping() {
            clearTimeout(this.state.timeoutID);
            this.state.isTyping = false;
            this.ui.content.innerHTML = this.state.pages[this.state.currentPage];
            this.ui.nextIndicator.style.display = 'block';
            this.updateIndicatorText();
        },

        /**
         * Avança para a próxima página ou redireciona se for a última.
         */
        nextPage() {
            if (this.state.currentPage >= this.state.pages.length - 1) {
                // Última página, redireciona
                window.location.href = this.state.nextUrl;
            } else {
                this.state.currentPage++;
                this.showPage(this.state.currentPage);
            }
        },

        /**
         * Prepara e exibe uma nova página de diálogo.
         */
        showPage(pageIndex) {
            this.state.isTyping = true;
            this.ui.content.innerHTML = '';
            this.ui.nextIndicator.style.display = 'none';
            this.typeWriter(this.state.pages[pageIndex]);
        },

        /**
         * Efeito de digitação (typewriter).
         */
        typeWriter(text, i = 0) {
            if (i < text.length) {
                this.ui.content.innerHTML += text.charAt(i);
                this.state.timeoutID = setTimeout(() => this.typeWriter(text, i + 1), 30); // Velocidade
            } else {
                this.state.isTyping = false;
                this.ui.nextIndicator.style.display = 'block';
                this.updateIndicatorText();
            }
        },

        /**
         * Atualiza o texto do indicador (ex: "CONTINUAR" ou "FORJAR HERÓI").
         */
        updateIndicatorText() {
            if (this.state.currentPage >= this.state.pages.length - 1) {
                this.ui.nextIndicator.textContent = "FORJAR HERÓI";
            } else {
                this.ui.nextIndicator.textContent = "CONTINUAR";
            }
        }
    };

    app.init();
});
</script>

</body>
</html>