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
        .attr-bar-wrapper {
            width: 100%;
            height: 28px;
            background: var(--ui-border-dark);
            border: 2px solid var(--ui-border-light);
            padding: 2px;
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
            cursor: pointer;
        }
        .attr-btn:active { transform: translateY(2px); }
        .attr-btn:disabled { background: #555; color: #999; cursor: not-allowed; }

        .btn {
            background: var(--ui-main); color: var(--text-light);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light);
            padding: 15px 35px; text-decoration: none;
            font-size: 1.2rem; transition: all 0.1s;
            cursor: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32" viewBox="0 0 8 8"><path fill="%23ffc800" d="M0 0v8l4-4-4-4z"/></svg>') 8 8, auto;
            font-family: 'Press Start 2P', cursive;
            text-transform: uppercase;
            margin-top: 20px;
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
        <p class="points-display">PONTOS: <span id="pointsLeft">75</span></p>

        <div id="attributesContainer">
            @php
                $attributes = ['hp' => 'HP', 'mp' => 'MP', 'attack' => 'ATAQUE', 'defense' => 'DEFESA', 'speed' => 'VELOCIDADE', 'special_attack' => 'AT. ESPECIAL', 'special_defense' => 'DEF. ESPECIAL'];
            @endphp

            @foreach($attributes as $attr => $label)
            <div class="attribute-row" data-attr="{{ $attr }}">
                <label for="{{ $attr }}">{{ $label }}</label>
                <div class="attr-bar-wrapper">
                    <div class="attr-bar-fill" id="{{ $attr }}Bar"></div>
                </div>
                <div class="attribute-controls">
                    <button type="button" class="attr-btn minus-btn" data-amount="5">-5</button>
                    <button type="button" class="attr-btn minus-btn" data-amount="1">-1</button>
                    <span class="attr-value" id="{{ $attr }}Value">0</span>
                    <button type="button" class="attr-btn plus-btn" data-amount="1">+1</button>
                    <button type="button" class="attr-btn plus-btn" data-amount="5">+5</button>
                    <input type="hidden" id="{{ $attr }}" name="{{ $attr }}" value="0">
                </div>
            </div>
            @endforeach
        </div>
        <button type="submit" id="submitBtn" class="btn" disabled>INICIAR JORNADA</button>
    </form>
</main>

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
        submitBtn.classList.toggle('no-shine', pointsLeft !== 0);

        if (pointsLeft === 0) {
            pointsDisplayEl.classList.add('complete');
        } else {
            pointsDisplayEl.classList.remove('complete');
        }

        document.querySelectorAll('.attribute-row').forEach(row => {
            const input = row.querySelector('input[type="hidden"]');
            const currentValue = parseInt(input.value);
            const bar = row.querySelector('.attr-bar-fill');
            bar.style.width = `${(currentValue / maxPerAttr) * 100}%`;
            
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

    updateUI();
});
</script>

</body>
</html>