<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>The Last SENAI | A Forja do Herói</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href="{{ asset('img/logo.png') }}">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700;800&family=IM+Fell+English:ital@0;1&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --wood-color: #5d4037;
            --wood-darker: #4e342e;
            --metal-color: #a9a9aa;
            --gold-color: #ffd700;
            --text-light: #f0e9d9; 
            --success-color: #4caf50;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'IM Fell English', serif;
            background: url("{{ asset('img/giphy.gif') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-blend-mode: multiply;
        }

        .overlay { position: fixed; inset: 0; background: rgba(0, 0, 0, 0.75); z-index: 0; }
        
        .main-container {
            position: relative; z-index: 1;
            padding: clamp(25px, 4vw, 40px);
            max-width: 1100px; 
            width: 100%;
            background-color: var(--wood-color);
            border: 15px solid;
            border-image: linear-gradient(45deg, var(--metal-color), #8b8b8b) 1;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.7);
            color: var(--text-light);
            opacity: 0;
            transform: translateY(20px);
            animation: fadeInUp 0.8s 0.2s forwards ease-out;
        }

        /* --- LAYOUT RESPONSIVO --- */
        .grid-layout {
            display: grid;
            grid-template-columns: 1fr; /* Padrão: 1 coluna para mobile */
            gap: 30px 40px;
        }

        /* Em telas maiores (desktops), muda para 2 colunas */
        @media (min-width: 992px) {
            .grid-layout {
                grid-template-columns: 1fr 1fr;
            }
            .grid-span-2 {
                grid-column: 1 / -1; /* Ocupa as duas colunas */
            }
            .column-left {
                border-right: 1px solid rgba(255, 215, 0, 0.2);
                padding-right: 40px;
            }
        }

        .column { display: flex; flex-direction: column; }
        .text-center { text-align: center; }

        h1 {
            font-family: 'IM Fell English SC', serif;
            font-size: clamp(2rem, 4vw, 2.8rem);
            color: var(--gold-color);
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
            margin-bottom: 20px;
            text-align: center;
        }

        h3 {
            font-family: 'Cinzel', serif;
            font-weight: 800;
            color: var(--gold-color);
            font-size: 1.6rem;
            margin-top: 25px;
            margin-bottom: 15px;
            border-bottom: 1px solid rgba(255, 215, 0, 0.3);
            padding-bottom: 10px;
            letter-spacing: 1px;
        }
        
        .tutorial-avatar {
            width: 120px; height: 120px;
            border-radius: 50%; border: 4px solid var(--gold-color);
            margin: 0 auto 20px auto;
            box-shadow: 0 0 15px rgba(255, 215, 0, 0.5);
        }

        p {
            font-size: 1.1rem; line-height: 1.7;
            margin-bottom: 15px; text-align: justify;
        }
        p strong, .highlight { color: var(--gold-color); font-weight: normal; }

        .tutorial-list { text-align: left; list-style: none; padding-left: 15px; }
        .tutorial-list li {
            font-size: 1.1rem; margin-bottom: 12px;
            line-height: 1.6; padding-left: 10px;
            position: relative;
        }
        .tutorial-list li::before {
            content: '•'; color: var(--gold-color);
            font-size: 1.5rem; position: absolute; left: -15px; top: -3px;
        }

        .points-display {
            font-family: 'Cinzel', serif; font-size: 1.5rem; text-align: center;
            margin-bottom: 20px; background: var(--wood-darker);
            padding: 10px; border-radius: 5px;
            border: 2px solid var(--metal-color);
            transition: all 0.3s;
        }
        .points-display.complete { border-color: var(--success-color); animation: pulse 1.5s infinite ease-in-out; }
        .points-display span { font-size: 2rem; font-weight: 700; color: var(--gold-color); }
        .points-display.complete span { color: var(--success-color); }

        .attribute-row {
            display: grid; grid-template-columns: 1fr;
            gap: 10px; margin-bottom: 12px;
            align-items: center;
        }
         @media (min-width: 480px) { /* Em telas um pouco maiores, o label fica ao lado */
            .attribute-row { grid-template-columns: 130px 1fr; }
        }

        .attribute-row label { font-size: 1.2rem; font-family: 'Cinzel', serif; text-align: left; }
        .attribute-controls { display: flex; align-items: center; justify-content: space-between; }
        .attr-value { font-family: 'Cinzel', serif; font-weight: 700; font-size: 1.5rem; text-align: center; color: var(--gold-color);}
        
        .attr-btn {
            font-family: 'Cinzel', serif; width: 35px; height: 35px;
            border-radius: 8px; border: 2px outset var(--metal-color);
            background: #c0c0c0; color: var(--wood-color);
            font-size: 1.2rem; font-weight: 700; cursor: pointer;
            transition: all 0.2s;
        }
        .attr-btn:hover:not(:disabled) { background: var(--gold-color); border-color: var(--gold-color); }
        .attr-btn:disabled { opacity: 0.4; cursor: not-allowed; border-style: solid; }

        .btn {
            display: inline-block; background: var(--gold-color);
            color: var(--wood-color); border: 3px outset var(--gold-color);
            padding: 15px 35px; text-decoration: none;
            font-weight: 700; font-size: 1.1rem;
            transition: all 0.3s ease-in-out; cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px; text-transform: uppercase;
            font-family: 'Cinzel', serif; margin-top: 20px;
            width: 100%;
        }
        .btn:hover:not(:disabled) {
            background-color: #ffed4a;
            box-shadow: 0 8px 20px rgba(255, 215, 0, 0.6);
            transform: translateY(-3px);
        }
        .btn:disabled {
            background: var(--metal-color); border-color: var(--metal-color);
            color: #777; opacity: 0.6; cursor: not-allowed;
            transform: none; box-shadow: none;
        }

        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
        @keyframes pulse { 0%, 100% { box-shadow: 0 0 15px rgba(76, 175, 80, 0.4); } 50% { box-shadow: 0 0 25px rgba(76, 175, 80, 0.9); } }
    </style>
</head>
<body>

<div class="overlay"></div>

<form class="main-container" method="POST" action="{{ route('character.allocate.store', $character->id) }}">
    @csrf
    <div class="grid-layout">
        <h1 class="grid-span-2">A Forja do Herói</h1>

        <div class="column column-left">
            <div class="text-center">
                <img src="{{ asset($character->avatar) }}" class="tutorial-avatar" alt="Avatar de {{ $character->name }}">
                <p>Saudações, <strong class="highlight">{{ $character->name }}</strong>! O destino de <strong>Pixelândia</strong> repousa sobre seus ombros.</p>
            </div>

            <h3>📜 A Lenda</h3>
            <p>O "Grande Reset" falhou, deixando para trás um <strong class="highlight">Eco Sombrio</strong> que corrompe a terra e manifesta criaturas de pesadelo. Sua missão é purificar o reino e descobrir o segredo por trás desta falha.</p>

            <h3>📜 Comandos de Batalha</h3>
            <ul class="tutorial-list">
                <li><strong>Ataque Físico:</strong> Um golpe básico que não custa MP.</li>
                <li><strong>Habilidades:</strong> Ataques poderosos que consomem MP.</li>
                <li><strong>Poção:</strong> Use para restaurar HP em um momento de aperto.</li>
            </ul>
        </div>

        <div class="column">
            <h3>⚔️ Molde seu Destino</h3>
            <p style="text-align: center;">Você tem pontos para distribuir e forjar suas habilidades. Gaste-os com sabedoria.</p>
            
            <p class="points-display">Pontos restantes: <span id="pointsLeft">75</span></p>

            <div id="attributesContainer">
                @php
                    $attributes = ['hp' => '❤️ HP', 'mp' => '✨ MP', 'attack' => '⚔️ Ataque', 'defense' => '🛡️ Defesa', 'speed' => '⚡ Velocidade', 'special_attack' => '🔥 At. Especial', 'special_defense' => '🔮 Def. Especial'];
                @endphp

                @foreach($attributes as $attr => $label)
                <div class="attribute-row" data-attr="{{ $attr }}">
                    <label for="{{ $attr }}">{{ $label }}:</label>
                    <div class="attribute-controls">
                        <button type="button" class="attr-btn minus-btn" data-amount="5">-5</button>
                        <button type="button" class="attr-btn minus-btn">-1</button>
                        <span class="attr-value" id="{{ $attr }}Value">0</span>
                        <button type="button" class="attr-btn plus-btn">+1</button>
                        <button type="button" class="attr-btn plus-btn" data-amount="5">+5</button>
                        <input type="hidden" id="{{ $attr }}" name="{{ $attr }}" value="0">
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        
        <div class="grid-span-2 text-center">
             <button type="submit" id="submitBtn" class="btn">Iniciar Jornada</button>
        </div>
    </div>
</form>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const totalPoints = 75;
    const maxPerAttr = 50;
    
    const pointsLeftEl = document.getElementById('pointsLeft');
    const pointsDisplayEl = pointsLeftEl.parentElement;
    const attributesContainer = document.getElementById('attributesContainer');
    const submitBtn = document.getElementById('submitBtn');
    
    let pointsLeft = totalPoints;

    const updateUI = () => {
        pointsLeftEl.textContent = pointsLeft;
        submitBtn.disabled = (pointsLeft !== 0);

        if (pointsLeft === 0) {
            pointsDisplayEl.classList.add('complete');
        } else {
            pointsDisplayEl.classList.remove('complete');
        }

        document.querySelectorAll('.attribute-row').forEach(row => {
            const input = row.querySelector('input[type="hidden"]');
            const currentValue = parseInt(input.value);
            
            const plusBtns = row.querySelectorAll('.plus-btn');
            const minusBtns = row.querySelectorAll('.minus-btn');

            plusBtns.forEach(btn => {
                const amount = parseInt(btn.dataset.amount) || 1;
                btn.disabled = (pointsLeft < amount || currentValue >= maxPerAttr);
            });
            minusBtns.forEach(btn => {
                const amount = parseInt(btn.dataset.amount) || 1;
                btn.disabled = (currentValue < amount);
            });
        });
    };

    attributesContainer.addEventListener('click', (event) => {
        const target = event.target;
        // Sai se o alvo não for um botão ou se estiver desabilitado
        if (!target.classList.contains('attr-btn') || target.disabled) return;

        const row = target.closest('.attribute-row');
        const attrName = row.dataset.attr;
        const input = document.getElementById(attrName);
        const valueEl = document.getElementById(`${attrName}Value`);
        let currentValue = parseInt(input.value);
        const amount = parseInt(target.dataset.amount) || 1;
        
        if (target.classList.contains('plus-btn')) {
            const addAmount = Math.min(amount, maxPerAttr - currentValue, pointsLeft);
            currentValue += addAmount;
            pointsLeft -= addAmount;
        } else if (target.classList.contains('minus-btn')) {
            const subAmount = Math.min(amount, currentValue);
            currentValue -= subAmount;
            pointsLeft += subAmount;
        }

        input.value = currentValue;
        valueEl.textContent = currentValue;
        
        updateUI();
    });

    updateUI(); // Inicia a interface com os valores e estados corretos
});
</script>

</body>
</html>