<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alocação de Pontos | {{ $character->name }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="icon" href= "img/logo.png">
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@400;700&family=IM+Fell+English:ital@0;1&family=IM+Fell+English+SC&display=swap" rel="stylesheet">
    
    <style>
        :root {
            --wood-color: #5d4037;
            --metal-color: #a9a9a9;
            --gold-color: #ffd700;
            --text-light: #f0e9d9;
        }

        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'IM Fell English', serif;
            background: #111 url("{{ asset('img/giphy.gif') }}") no-repeat center center fixed;
            background-size: cover;
            text-align: center;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
            background-blend-mode: multiply;
        }

        .overlay {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.7);
            z-index: 0;
        }
        
        .main-container {
            position: relative;
            z-index: 1;
            padding: clamp(30px, 5vw, 50px) clamp(20px, 4vw, 40px);
            max-width: 600px;
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

        h1 {
            font-family: 'IM Fell English SC', serif;
            font-size: clamp(2rem, 5vw, 2.5rem);
            color: var(--gold-color);
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.8);
            margin-bottom: 20px;
        }

        /* MELHORIA: Display de pontos em destaque */
        .points-display {
            font-family: 'Cinzel', serif;
            font-size: 1.5rem;
            margin-bottom: 30px;
        }

        .points-display span {
            font-size: 2rem;
            font-weight: 700;
            color: var(--gold-color);
        }

        /* MELHORIA: Linha de cada atributo */
        .attribute-row {
            display: grid;
            grid-template-columns: 1fr auto;
            align-items: center;
            padding: 10px 0;
            border-bottom: 1px solid rgba(240, 233, 217, 0.2);
        }
        
        .attribute-row label {
            text-align: left;
            font-size: 1.2rem;
        }

        /* MELHORIA: Controles interativos (+/-) */
        .attribute-controls {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .attr-btn {
            font-family: 'Cinzel', serif;
            width: 35px;
            height: 35px;
            border-radius: 50%;
            border: 2px outset var(--metal-color);
            background: #c0c0c0;
            color: var(--wood-color);
            font-size: 1.5rem;
            font-weight: 700;
            cursor: pointer;
            transition: all 0.2s;
        }
        
        .attr-btn:hover:not(:disabled) {
            background: var(--gold-color);
            border-color: var(--gold-color);
        }

        .attr-btn:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            border-style: solid;
        }

        .attr-value {
            font-family: 'Cinzel', serif;
            font-weight: 700;
            font-size: 1.5rem;
            width: 50px; /* Largura fixa para o número não "pular" */
        }
        
        .btn {
            display: inline-block;
            background: var(--gold-color);
            color: var(--wood-color);
            border: 3px outset var(--gold-color);
            padding: 15px 35px;
            text-decoration: none;
            font-weight: 700;
            font-size: 1rem;
            transition: all 0.2s ease-in-out;
            cursor: pointer;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.5);
            letter-spacing: 1px;
            text-transform: uppercase;
            font-family: 'Cinzel', serif;
            margin-top: 30px;
        }

        .btn:disabled {
            background: var(--metal-color);
            border-color: var(--metal-color);
            color: #888;
            opacity: 0.7;
            cursor: not-allowed;
            transform: none;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.3);
        }

        @keyframes fadeInUp { to { opacity: 1; transform: translateY(0); } }
    </style>
</head>
<body>

<div class="overlay"></div>

<main class="main-container">
    <h1>💪 Distribua seus Pontos</h1>
    <p class="points-display">Pontos restantes: <span id="pointsLeft">75</span></p>

    <form method="POST" action="{{ route('character.allocate.store', $character->id) }}">
        @csrf
        @php
            $attributes = ['hp' => 'HP', 'mp' => 'MP', 'attack' => 'Ataque', 'defense' => 'Defesa', 'speed' => 'Velocidade', 'special_attack' => 'Ataque Especial', 'special_defense' => 'Defesa Especial'];
            $maxPerAttribute = 50;
            $totalPoints = 75;
        @endphp

        <div id="attributesContainer">
            @foreach($attributes as $attr => $label)
            <div class="attribute-row" data-attr="{{ $attr }}">
                <label for="{{ $attr }}">{{ $label }}:</label>
                <div class="attribute-controls">
                    <button type="button" class="attr-btn minus-btn" aria-label="Diminuir {{ $label }}">-</button>
                    <span class="attr-value" id="{{ $attr }}Value">0</span>
                    <button type="button" class="attr-btn plus-btn" aria-label="Aumentar {{ $label }}">+</button>
                    <input type="hidden" id="{{ $attr }}" name="{{ $attr }}" value="0">
                </div>
            </div>
            @endforeach
        </div>

        <button type="submit" id="submitBtn" class="btn">Confirmar Atributos</button>
    </form>
</main>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const totalPoints = {{ $totalPoints }};
    const maxPerAttr = {{ $maxPerAttribute }};
    
    const pointsLeftEl = document.getElementById('pointsLeft');
    const attributesContainer = document.getElementById('attributesContainer');
    const submitBtn = document.getElementById('submitBtn');
    
    let pointsLeft = totalPoints;

    const updateUI = () => {
        // 1. Atualiza o contador de pontos
        pointsLeftEl.textContent = pointsLeft;

        // 2. Habilita/desabilita o botão de confirmar
        submitBtn.disabled = (pointsLeft !== 0);

        // 3. Itera em cada atributo para atualizar seus botões
        document.querySelectorAll('.attribute-row').forEach(row => {
            const attrName = row.dataset.attr;
            const input = document.getElementById(attrName);
            const currentValue = parseInt(input.value);
            
            const plusBtn = row.querySelector('.plus-btn');
            const minusBtn = row.querySelector('.minus-btn');

            // Desabilita o '+' se não houver mais pontos ou se o atributo atingiu o máximo
            plusBtn.disabled = (pointsLeft === 0 || currentValue >= maxPerAttr);
            // Desabilita o '-' se o atributo já estiver em 0
            minusBtn.disabled = (currentValue === 0);
        });
    };

    // MELHORIA: Usando delegação de eventos para mais eficiência
    attributesContainer.addEventListener('click', (event) => {
        const target = event.target;
        if (!target.classList.contains('attr-btn')) return; // Sai se não clicou em um botão

        const row = target.closest('.attribute-row');
        const attrName = row.dataset.attr;
        const input = document.getElementById(attrName);
        const valueEl = document.getElementById(`${attrName}Value`);
        let currentValue = parseInt(input.value);

        if (target.classList.contains('plus-btn')) {
            if (pointsLeft > 0 && currentValue < maxPerAttr) {
                currentValue++;
                pointsLeft--;
            }
        } else if (target.classList.contains('minus-btn')) {
            if (currentValue > 0) {
                currentValue--;
                pointsLeft++;
            }
        }

        input.value = currentValue;
        valueEl.textContent = currentValue;
        
        updateUI(); // Atualiza toda a interface após qualquer mudança
    });

    // Inicia a interface com os valores e estados corretos
    updateUI();
});
</script>

</body>
</html>