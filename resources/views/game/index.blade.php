<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Last SENAI | Forje seu Herói</title>
    
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
            --danger-color: #e53935; /* Cor para erros */
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
        
        .main-container {
            position: relative; z-index: 1;
            padding: clamp(20px, 4vw, 30px);
            max-width: 1100px;
            width: 100%;
            background: var(--ui-main);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light), 0 10px 30px rgba(0,0,0,0.5);
            opacity: 0;
            animation: fadeIn 0.5s 0.2s forwards;
        }
        
        .creation-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 30px;
        }

        @media (min-width: 992px) {
            .creation-grid { grid-template-columns: 320px 1fr; }
        }

        h1 {
            font-size: clamp(2rem, 5vw, 2.8rem);
            color: var(--text-highlight);
            text-shadow: 3px 3px #000;
            margin: 0 0 30px;
            text-align: center;
            grid-column: 1 / -1; /* Ocupa todas as colunas */
        }

        /* --- Melhoria: Caixa de Erros de Validação --- */
        .error-box {
            grid-column: 1 / -1; /* Ocupa todas as colunas */
            background: var(--danger-color);
            border: 4px solid var(--ui-border-dark);
            padding: 20px;
            margin-bottom: 10px;
            text-align: left;
        }
        .error-box-title {
            font-size: 1rem;
            color: var(--text-light);
            text-shadow: 2px 2px #000;
            margin-bottom: 15px;
        }
        .error-list {
            list-style: none;
            padding-left: 0;
            font-size: 0.8rem;
            line-height: 1.6;
        }
        /* --- Fim da Caixa de Erros --- */

        /* Melhoria: Trocado fieldset por label onde faz mais sentido */
        .form-group { margin-bottom: 25px; }

        .form-legend {
            font-size: 1rem;
            color: var(--text-highlight);
            margin-bottom: 15px;
            width: 100%;
            text-align: left;
            text-shadow: 2px 2px #000;
            display: block; /* Para a <label> se comportar como a <legend> */
        }
        
        .form-fieldset { border: none; padding: 0; margin-bottom: 25px; }

        .form-input {
            width: 100%;
            padding: 15px;
            background: var(--bg-dark);
            border: 2px solid var(--ui-border-light);
            font-family: 'Press Start 2P', cursive;
            font-size: 1rem;
            color: var(--text-light);
            outline: none;
        }
        /* Melhoria: :focus-visible para acessibilidade sem poluir o clique */
        .form-input:focus-visible {
            outline: 2px solid var(--text-highlight);
            border-color: var(--text-highlight);
        }

        /* --- Melhorias A11y: Estilos para Radiogroup --- */
        .selection-grid, .avatar-gallery {
            outline: none; /* O foco será gerenciado nos filhos */
        }
        
        .selection-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 10px;
        }
        
        .class-option {
            background: var(--ui-border-dark);
            border: 2px solid var(--ui-border-light);
            padding: 15px 10px;
            cursor: var(--cursor-pointer); /* Cursor customizado */
            transition: all 0.2s;
            text-align: center;
            color: var(--text-light);
            outline: none; /* Outline customizado abaixo */
        }
        .class-option:hover { background: var(--ui-border-light); color: var(--bg-dark); }
        
        /* [aria-checked="true"] é mais semântico que .selected */
        .class-option[aria-checked="true"] {
            background: var(--text-highlight);
            color: var(--bg-dark);
            border-color: var(--text-light);
        }
        .class-option:focus-visible {
            box-shadow: 0 0 0 3px var(--bg-dark), 0 0 0 5px var(--text-highlight);
        }

        .class-icon { font-size: 2rem; margin-bottom: 10px; pointer-events: none; } /* Impede que o clique caia no emoji */
        .class-name { font-size: 0.8rem; pointer-events: none; }

        .avatar-gallery { display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr)); gap: 10px; }
        .avatar-option {
            width: 100%; aspect-ratio: 1 / 1;
            cursor: var(--cursor-pointer);
            border: 4px solid transparent;
            transition: all 0.2s;
            object-fit: cover;
            background: var(--bg-dark);
            outline: none;
        }
        .avatar-option:hover { border-color: var(--ui-border-light); }
        .avatar-option[aria-checked="true"] { border-color: var(--text-highlight); }
        .avatar-option:focus-visible {
            border-color: var(--text-highlight);
            box-shadow: 0 0 0 3px var(--bg-dark), 0 0 0 5px var(--text-highlight);
        }
        
        .preview-card {
            background: var(--bg-dark);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light);
            padding: 20px;
            text-align: center;
        }
        .preview-avatar {
            width: 150px; height: 150px;
            background-color: #000;
            margin: 0 auto 15px auto;
            border: 4px solid var(--ui-border-light);
            object-fit: cover;
            display: block;
        }
        .preview-name {
            font-size: 1.5rem;
            color: var(--text-highlight);
            text-shadow: 2px 2px #000;
            min-height: 2.5rem; /* Evita pulos de layout */
            word-break: break-all;
        }
        .preview-stats {
            margin-top: 20px;
            list-style: none;
            padding: 15px;
            text-align: left;
            font-size: 0.9rem;
            line-height: 1.8;
            background-color: var(--ui-main);
        }
        
        .btn {
            background: var(--ui-main); color: var(--text-light);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light);
            padding: 15px 35px; text-decoration: none;
            font-size: 1.2rem; transition: all 0.1s;
            cursor: var(--cursor-pointer);
            font-family: 'Press Start 2P', cursive;
            text-transform: uppercase;
            width: 100%; /* Botão ocupa toda a largura */
        }
        .btn:hover:not(:disabled) { background: var(--ui-border-light); color: var(--bg-dark); }
        .btn:disabled { background: #555; color: #999; cursor: not-allowed; border-color: #333; box-shadow: inset 0 0 0 4px #777; }

        /* Helper class para o 'Criar Jogo' */
        .grid-span-full {
             /* Em telas > 992px, faz o botão ocupar as duas colunas */
            @media (min-width: 992px) { grid-column: 1 / -1; }
        }

        @keyframes fadeIn { to { opacity: 1; } }
    </style>
</head>
<body>

<div class="overlay"></div>

<main class="main-container">
    <form id="characterForm" method="POST" action="{{ route('character.store') }}">
        @csrf
        <div class="creation-grid">
            <h1>CRIAR HERÓI</h1>

            @if ($errors->any())
                <div class="error-box">
                    <h2 class="error-box-title">ERRO NA CRIAÇÃO:</h2>
                    <ul class="error-list">
                        @foreach ($errors->all() as $error)
                            <li>- {{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            
            <aside class="character-preview-panel">
                <div class="preview-card">
                    <img src="{{ asset('img/avatar-8.png') }}" alt="Avatar Preview" id="avatarPreview" class="preview-avatar">
                    <h2 id="namePreview" class="preview-name">[NOME]</h2>
                    <ul class="preview-stats">
                        <li>HP: <span id="stat-hp">--</span></li>
                        <li>ATAQUE: <span id="stat-attack">--</span></li>
                        <li>DEFESA: <span id="stat-defense">--</span></li>
                    </ul>
                </div>
            </aside>

            <section class="creation-panel">
                <div class="form-group">
                    <label for="nameInput" class="form-legend">NOME:</label>
                    <input type="text" id="nameInput" name="name" class="form-input" 
                           required minlength="3" autocomplete="off"
                           value="{{ old('name') }}"> </div>
                
                <fieldset class="form-fieldset">
                    <legend class="form-legend" id="class-legend">CLASSE:</legend>
                    <div class="selection-grid" id="classSelection" 
                         role="radiogroup" aria-labelledby="class-legend">
                        
                        <div class="class-option" role="radio" aria-checked="false" tabindex="-1"
                             data-class="guerreiro" data-hp="100" data-attack="12" data-defense="10">
                            <div class="class-icon">⚔️</div>
                            <div class="class-name">Guerreiro</div>
                        </div>
                        <div class="class-option" role="radio" aria-checked="false" tabindex="-1"
                             data-class="mago" data-hp="80" data-attack="15" data-defense="6">
                            <div class="class-icon">🔮</div>
                            <div class="class-name">Mago</div>
                        </div>
                         <div class="class-option" role="radio" aria-checked="false" tabindex="-1"
                             data-class="arqueiro" data-hp="80" data-attack="13" data-defense="8">
                            <div class="class-icon">🏹</div>
                            <div class="class-name">Arqueiro</div>
                        </div>
                    </div>
                    <input type="hidden" name="class" id="classInput" required value="{{ old('class') }}">
                </fieldset>

                <fieldset class="form-fieldset">
                    <legend class="form-legend" id="avatar-legend">AVATAR:</legend>
                    <div class="avatar-gallery" id="avatarGallery"
                         role="radiogroup" aria-labelledby="avatar-legend">
                        
                        <img src="{{ asset('img/avatar-1.png') }}" alt="Avatar 1" class="avatar-option" role="radio" aria-checked="false" tabindex="-1" data-value="img/avatar-1.png">
                        <img src="{{ asset('img/avatar-2.png') }}" alt="Avatar 2" class="avatar-option" role="radio" aria-checked="false" tabindex="-1" data-value="img/avatar-2.png">
                        <img src="{{ asset('img/avatar-3.png') }}" alt="Avatar 3" class="avatar-option" role="radio" aria-checked="false" tabindex="-1" data-value="img/avatar-3.png">
                        <img src="{{ asset('img/avatar-5.png') }}" alt="Avatar 5" class="avatar-option" role="radio" aria-checked="false" tabindex="-1" data-value="img/avatar-5.png">
                        <img src="{{ asset('img/avatar-6.png') }}" alt="Avatar 6" class="avatar-option" role="radio" aria-checked="false" tabindex="-1" data-value="img/avatar-6.png">
                        <img src="{{ asset('img/avatar-7.png') }}" alt="Avatar 7" class="avatar-option" role="radio" aria-checked="false" tabindex="-1" data-value="img/avatar-7.png">
                    </div>
                    <input type="hidden" name="avatar" id="avatarInput" required value="{{ old('avatar') }}">
                </fieldset>
            </section>
            
            <div class="grid-span-full">
                <button type="submit" id="submitBtn" class="btn" disabled>INICIAR JOGO</button>
            </div>
        </div>
    </form>
</main>

<script>
// Melhoria: Encapsulando a lógica em um objeto 'app'
document.addEventListener('DOMContentLoaded', () => {
    const app = {
        ui: {
            form: document.getElementById('characterForm'),
            nameInput: document.getElementById('nameInput'),
            avatarInput: document.getElementById('avatarInput'),
            classInput: document.getElementById('classInput'),
            avatarGallery: document.getElementById('avatarGallery'),
            classSelection: document.getElementById('classSelection'),
            submitBtn: document.getElementById('submitBtn'),
            // Preview Elements
            namePreview: document.getElementById('namePreview'),
            avatarPreview: document.getElementById('avatarPreview'),
            statHp: document.getElementById('stat-hp'),
            statAttack: document.getElementById('stat-attack'),
            statDefense: document.getElementById('stat-defense'),
        },
        
        init() {
            this.bindEvents();
            this.initFromOldData(); // Melhoria: Inicializa com dados do old()
            this.validateForm();
        },

        bindEvents() {
            this.ui.nameInput.addEventListener('input', () => {
                const currentName = this.ui.nameInput.value.trim();
                this.ui.namePreview.textContent = currentName === '' ? '[NOME]' : currentName;
                this.validateForm();
            });

            // Melhoria A11y: Usando a função de radiogroup
            this.initRadioGroup(this.ui.classSelection, (selected) => {
                this.ui.classInput.value = selected.dataset.class;
                this.ui.statHp.textContent = selected.dataset.hp;
                this.ui.statAttack.textContent = selected.dataset.attack;
                this.ui.statDefense.textContent = selected.dataset.defense;
                this.validateForm();
            });

            // Melhoria A11y: Usando a função de radiogroup
            this.initRadioGroup(this.ui.avatarGallery, (selected) => {
                this.ui.avatarInput.value = selected.dataset.value;
                this.ui.avatarPreview.src = selected.src; // 'src' para <img>
                this.validateForm();
            });
        },
        
        /**
         * Melhoria: Inicializa o estado do formulário com base nos
         * valores 'old()' do Laravel, se existirem.
         */
        initFromOldData() {
            const oldName = this.ui.nameInput.value.trim();
            if (oldName) {
                this.ui.namePreview.textContent = oldName;
            }

            const oldClass = this.ui.classInput.value;
            if (oldClass) {
                const classOption = this.ui.classSelection.querySelector(`[data-class="${oldClass}"]`);
                if (classOption) {
                    this.selectRadioOption(this.ui.classSelection, classOption, false); // false = não focar
                }
            }

            const oldAvatar = this.ui.avatarInput.value;
            if (oldAvatar) {
                const avatarOption = this.ui.avatarGallery.querySelector(`[data-value="${oldAvatar}"]`);
                if (avatarOption) {
                    this.selectRadioOption(this.ui.avatarGallery, avatarOption, false);
                }
            }
        },

        validateForm() {
            const isNameValid = this.ui.nameInput.value.trim().length >= 3;
            const isAvatarSelected = this.ui.avatarInput.value !== '';
            const isClassSelected = this.ui.classInput.value !== '';
            this.ui.submitBtn.disabled = !(isNameValid && isAvatarSelected && isClassSelected);
        },
        
        /**
         * Melhoria A11y: Transforma um container em um radiogroup
         * acessível por teclado (setas).
         * @param {HTMLElement} container - O elemento com role="radiogroup"
         * @param {Function} onSelect - Callback executado ao selecionar
         */
        initRadioGroup(container, onSelect) {
            const options = Array.from(container.querySelectorAll('[role="radio"]'));
            if (options.length === 0) return;

            // O primeiro item é focável por Tab
            options[0].setAttribute('tabindex', '0');

            container.addEventListener('click', (e) => {
                const target = e.target.closest('[role="radio"]');
                if (!target) return;
                this.selectRadioOption(container, target, true);
                onSelect(target);
            });
            
            container.addEventListener('keydown', (e) => {
                const target = e.target.closest('[role="radio"]');
                if (!target) return;

                let newTarget = null;
                const currentIndex = options.indexOf(target);

                if (e.key === 'ArrowRight' || e.key === 'ArrowDown') {
                    e.preventDefault();
                    newTarget = options[(currentIndex + 1) % options.length];
                } else if (e.key === 'ArrowLeft' || e.key === 'ArrowUp') {
                    e.preventDefault();
                    newTarget = options[(currentIndex - 1 + options.length) % options.length];
                } else if (e.key === ' ' || e.key === 'Enter') {
                    e.preventDefault();
                    // O item já está focado, apenas disparamos a seleção
                    this.selectRadioOption(container, target, true);
                    onSelect(target);
                }
                
                if (newTarget) {
                    // Move o foco e seleciona
                    this.selectRadioOption(container, newTarget, true);
                    onSelect(newTarget);
                }
            });
        },
        
        /**
         * Helper A11y: Atualiza os estados de um radiogroup
         * @param {HTMLElement} container - O radiogroup
         * @param {HTMLElement} optionToSelect - A opção a ser marcada
         * @param {boolean} focus - Deve focar na opção?
         */
        selectRadioOption(container, optionToSelect, focus = true) {
            const options = container.querySelectorAll('[role="radio"]');
            
            options.forEach(opt => {
                opt.setAttribute('aria-checked', 'false');
                opt.setAttribute('tabindex', '-1');
            });
            
            optionToSelect.setAttribute('aria-checked', 'true');
            optionToSelect.setAttribute('tabindex', '0');
            
            if (focus) {
                optionToSelect.focus();
            }
        }
    };

    app.init();
});
</script>

</body>
</html>