<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Last SENAI | A Forja do Herói</title>
    
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
            --success-color: #7cb342;
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
            max-width: 800px;
            width: 100%;
            background: var(--ui-main);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light), 0 10px 30px rgba(0,0,0,0.5);
            opacity: 0;
            animation: fadeIn 0.5s 0.2s forwards;
            text-align: center;
        }
        
        h1 {
            font-size: clamp(1.8rem, 5vw, 2.5rem);
            color: var(--text-highlight);
            text-shadow: 3px 3px #000;
            margin: 0 0 30px;
        }

        .points-display {
            font-size: 1.2rem;
            margin-bottom: 30px;
            background: var(--bg-dark);
            padding: 15px;
            border: 2px solid var(--ui-border-light);
            transition: all 0.3s;
        }
        .points-display.complete { border-color: var(--success-color); animation: pulse 1.5s infinite; }
        .points-display span { font-size: 1.8rem; color: var(--text-highlight); animation: blink-text 2s infinite; }
        .points-display.complete span { color: var(--success-color); animation: none; }

        .attribute-row {
            margin-bottom: 20px;
            text-align: left;
        }
        .attribute-row label {
            font-size: 1rem;
            display: block;
            margin-bottom: 10px;
        }
        
        /* Melhoria A11y: A barra agora é o "slider" focável */
        .attr-bar-wrapper {
            width: 100%;
            height: 28px;
            background: var(--ui-border-dark);
            border: 2px solid var(--ui-border-light);
            padding: 2px;
            outline: none; /* Foco customizado abaixo */
        }
        /* Melhoria A11y: Estado de foco para navegação com teclado */
        .attr-bar-wrapper:focus-visible {
            border-color: var(--text-highlight);
            box-shadow: 0 0 0 3px var(--bg-dark), 0 0 0 5px var(--text-highlight);
        }
        
        .attr-bar-fill {
            width: 0;
            height: 100%;
            background-color: var(--text-highlight);
            transition: width 0.2s linear;
        }
        .attribute-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 10px;
        }
        .attr-value { font-size: 1.2rem; color: var(--text-light); }
        .attr-btn {
            background: var(--ui-border-light);
            color: var(--ui-border-dark);
            border: 2px solid var(--ui-border-dark);
            width: 45px; height: 35px;
            font-family: inherit; font-size: 1rem;
            cursor: var(--cursor-pointer); /* Usando variável */
            outline: none;
        }
        .attr-btn:active { transform: translateY(2px); }
        .attr-btn:disabled { background: #555; color: #999; cursor: not-allowed; }
        /* Melhoria A11y: Estado de foco para os botões */
        .attr-btn:focus-visible {
            border-color: var(--text-highlight);
            box-shadow: 0 0 0 2px var(--bg-dark), 0 0 0 4px var(--text-highlight);
        }

        .btn {
            background: var(--ui-main); color: var(--text-light);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light);
            padding: 15px 35px; text-decoration: none;
            font-size: 1.2rem; transition: all 0.1s;
            cursor: var(--cursor-pointer); /* Usando variável */
            font-family: 'Press Start 2P', cursive;
            text-transform: uppercase;
            margin-top: 20px;
            width: 100%; /* Botão ocupa largura total */
        }
        .btn:hover:not(:disabled) { background: var(--ui-border-light); color: var(--bg-dark); }
        .btn:disabled { background: #555; color: #999; cursor: not-allowed; border-color: #333; box-shadow: inset 0 0 0 4px #777; animation: none; }
        .btn:not(:disabled) { animation: shine 2s infinite; }
        
        @keyframes fadeIn { to { opacity: 1; } }
        @keyframes pulse { 0%, 100% { box-shadow: 0 0 10px var(--success-color); } 50% { box-shadow: 0 0 20px var(--success-color); } }
        @keyframes blink-text { 50% { opacity: 0.7; } }
        @keyframes shine { 0%, 100% { box-shadow: inset 0 0 0 4px var(--ui-border-light); } 50% { box-shadow: inset 0 0 0 4px var(--text-highlight); } }
    </style>
</head>
<body>

<div class="overlay"></div>

<main class="main-container">
    <form method="POST" action="{{ route('character.allocate.store', $character->id) }}">
        @csrf
        <h1>FORJA DO HERÓI</h1>
        
        <p class="points-display" aria-live="polite">PONTOS: <span id="pointsLeft">75</span></p>

        <div id="attributesContainer">
            @php
                $attributes = ['hp' => 'HP', 'mp' => 'MP', 'attack' => 'ATAQUE', 'defense' => 'DEFESA', 'speed' => 'VELOCIDADE', 'special_attack' => 'AT. ESPECIAL', 'special_defense' => 'DEF. ESPECIAL'];
                $maxPerAttr = 50; // Definido aqui para ser usado no loop
            @endphp

            @foreach($attributes as $attr => $label)
            <div class="attribute-row" data-attr="{{ $attr }}">
                <label id="{{ $attr }}Label">{{ $label }}</label>
                
                <div class="attr-bar-wrapper" 
                     role="slider" 
                     tabindex="0" 
                     aria-labelledby="{{ $attr }}Label"
                     aria-valuemin="0"
                     aria-valuemax="{{ $maxPerAttr }}"
                     aria-valuenow="{{ old($attr, 0) }}"
                     aria-valuetext="{{ old($attr, 0) }} PONTOS">
                    <div class="attr-bar-fill" id="{{ $attr }}Bar"></div>
                </div>
                
                <div class="attribute-controls">
                    <button type="button" class="attr-btn minus-btn" data-amount="5">-5</button>
                    <button type="button" class="attr-btn minus-btn" data-amount="1">-1</button>
                    <span class="attr-value" id="{{ $attr }}Value">0</span> <button type="button" class="attr-btn plus-btn" data-amount="1">+1</button>
                    <button type="button" class="attr-btn plus-btn" data-amount="5">+5</button>
                    
                    <input type="hidden" id="{{ $attr }}" name="{{ $attr }}" value="{{ old($attr, 0) }}">
                </div>
            </div>
            @endforeach
        </div>
        <button type="submit" id="submitBtn" class="btn" disabled>INICIAR JORNADA</button>
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const app = {
        consts: {
            TOTAL_POINTS: 75,
            MAX_PER_ATTR: 50,
        },
        state: {
            pointsLeft: 75,
        },
        ui: {
            pointsLeftEl: document.getElementById('pointsLeft'),
            pointsDisplayEl: document.getElementById('pointsLeft').parentElement,
            attributesContainer: document.getElementById('attributesContainer'),
            attributeRows: document.querySelectorAll('.attribute-row'),
            submitBtn: document.getElementById('submitBtn'),
        },

        init() {
            this.state.pointsLeft = this.consts.TOTAL_POINTS; // Reseta
            let allocatedPoints = 0;

            // Melhoria Robustez: Lê os valores 'old()' do HTML
            this.ui.attributeRows.forEach(row => {
                const input = row.querySelector('input[type="hidden"]');
                const valueEl = row.querySelector('.attr-value');
                const slider = row.querySelector('[role="slider"]');
                
                const currentValue = parseInt(input.value); // Pega o valor 'old(x, 0)'
                allocatedPoints += currentValue;
                
                // Atualiza o texto e ARIA com o valor 'old()'
                valueEl.textContent = currentValue;
                slider.setAttribute('aria-valuenow', currentValue);
                slider.setAttribute('aria-valuetext', `${currentValue} PONTOS`);
            });

            this.state.pointsLeft -= allocatedPoints; // Subtrai o que já foi alocado
            
            this.bindEvents();
            this.updateUI(); // Chama o update para definir o estado inicial correto das barras/botões
        },

        bindEvents() {
            this.ui.attributesContainer.addEventListener('click', e => this.handleClick(e));
            // Melhoria A11y: Adiciona listener de teclado para os sliders
            this.ui.attributesContainer.addEventListener('keydown', e => this.handleKeydown(e));
        },

        /**
         * Lida com cliques nos botões +/-
         */
        handleClick(event) {
            const target = event.target;
            if (!target.classList.contains('attr-btn') || target.disabled) return;

            const row = target.closest('.attribute-row');
            const attrName = row.dataset.attr;
            const amount = parseInt(target.dataset.amount) || 1;
            
            const change = target.classList.contains('plus-btn') ? amount : -amount;
            this.updateAttribute(attrName, change);
        },

        /**
         * Melhoria A11y: Lida com navegação por teclado no slider
         */
        handleKeydown(event) {
            const slider = event.target.closest('[role="slider"]');
            if (!slider) return;

            const row = slider.closest('.attribute-row');
            const attrName = row.dataset.attr;
            const input = document.getElementById(attrName);
            const currentValue = parseInt(input.value);
            let change = 0;

            switch (event.key) {
                case 'ArrowRight':
                    change = 1;
                    break;
                case 'ArrowLeft':
                    change = -1;
                    break;
                case 'PageUp':
                    change = 5;
                    break;
                case 'PageDown':
                    change = -5;
                    break;
                case 'Home':
                    // Define para 0
                    change = -currentValue;
                    break;
                case 'End':
                    // Tenta definir para o máximo
                    const maxAdd = Math.min(this.consts.MAX_PER_ATTR - currentValue, this.state.pointsLeft);
                    change = maxAdd;
                    break;
                default:
                    return; // Ignora outras teclas
            }

            event.preventDefault(); // Impede o scroll da página
            if (change !== 0) {
                this.updateAttribute(attrName, change);
            }
        },

        /**
         * Lógica central para alterar um atributo
         * @param {string} attrName - O nome do atributo (ex: 'hp')
         * @param {number} amount - O valor para adicionar (pode ser negativo)
         */
        updateAttribute(attrName, amount) {
            const row = this.ui.attributesContainer.querySelector(`[data-attr="${attrName}"]`);
            if (!row) return;

            const input = document.getElementById(attrName);
            const valueEl = document.getElementById(`${attrName}Value`);
            const slider = row.querySelector('[role="slider"]');
            let currentValue = parseInt(input.value);

            let change = 0;
            if (amount > 0) { // Adicionando pontos
                const addAmount = Math.min(amount, this.consts.MAX_PER_ATTR - currentValue, this.state.pointsLeft);
                currentValue += addAmount;
                this.state.pointsLeft -= addAmount;
                change = addAmount;
            } else if (amount < 0) { // Removendo pontos
                const subAmount = Math.min(Math.abs(amount), currentValue);
                currentValue -= subAmount;
                this.state.pointsLeft += subAmount;
                change = -subAmount;
            }

            // Só atualiza a UI se algo mudou
            if (change !== 0) {
                input.value = currentValue;
                valueEl.textContent = currentValue;
                // Melhoria A11y: Atualiza os valores do slider
                slider.setAttribute('aria-valuenow', currentValue);
                slider.setAttribute('aria-valuetext', `${currentValue} PONTOS`);
                
                this.updateUI(); // Atualiza os estados globais (botões, pontos)
            }
        },

        /**
         * Atualiza a UI global (pontos restantes, barras, estados dos botões)
         */
        updateUI() {
            this.ui.pointsLeftEl.textContent = this.state.pointsLeft;
            this.ui.submitBtn.disabled = (this.state.pointsLeft !== 0);
            
            this.ui.pointsDisplayEl.classList.toggle('complete', this.state.pointsLeft === 0);

            this.ui.attributeRows.forEach(row => {
                const input = row.querySelector('input[type="hidden"]');
                const currentValue = parseInt(input.value);
                const bar = row.querySelector('.attr-bar-fill');
                
                // Atualiza a barra de progresso
                bar.style.width = `${(currentValue / this.consts.MAX_PER_ATTR) * 100}%`;
                
                // Habilita/Desabilita botões
                row.querySelectorAll('.plus-btn').forEach(btn => {
                    const amount = parseInt(btn.dataset.amount) || 1;
                    btn.disabled = (this.state.pointsLeft < amount || currentValue >= this.consts.MAX_PER_ATTR);
                });
                
                row.querySelectorAll('.minus-btn').forEach(btn => {
                    const amount = parseInt(btn.dataset.amount) || 1;
                    btn.disabled = (currentValue < amount);
                });
            });
        }
    };

    app.init();
});
</script>

</body>
</html>