<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>A Caravana Mágica</title>
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
            --error-color: #e53935;
            --rarity-common: #ffffff; --rarity-uncommon: #1eff00; --rarity-rare: #0070dd;
        }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Press Start 2P', cursive;
            background: url("{{ asset('img/shop_bg.gif') }}") no-repeat center center fixed;
            background-size: cover;
            min-height: 100vh;
            display: flex; justify-content: center; align-items: center;
            padding: 20px;
            background-color: var(--bg-dark);
            background-blend-mode: multiply;
            color: var(--text-light);
            image-rendering: pixelated;
        }

        .shop-container {
            position: relative; z-index: 1;
            padding: clamp(20px, 4vw, 30px);
            max-width: 1200px; width: 100%;
            background: var(--ui-main);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light), 0 10px 30px rgba(0,0,0,0.5);
            opacity: 0;
            animation: fadeIn 0.5s 0.2s forwards;
        }

        .shop-header {
            text-align: center;
            margin-bottom: 20px;
            padding-bottom: 20px;
            border-bottom: 4px solid var(--ui-border-dark);
        }

        h1 { font-size: clamp(1.8rem, 5vw, 2.5rem); color: var(--text-highlight); text-shadow: 3px 3px #000; }
        .shop-header p { font-size: 0.9rem; max-width: 600px; margin: 15px auto 0 auto; line-height: 1.6; }
        
        .shop-layout {
            display: grid;
            grid-template-columns: 1fr;
            gap: 20px;
        }
        @media (min-width: 992px) {
            .shop-layout { grid-template-columns: 300px 1fr; }
        }

        .panel {
            background: var(--bg-dark);
            border: 4px solid var(--ui-border-dark);
            box-shadow: inset 0 0 0 4px var(--ui-border-light);
            padding: 20px;
        }
        
        .panel h3 { font-size: 1.2rem; color: var(--text-highlight); margin: 0 0 15px 0; text-shadow: 2px 2px #000; }
        .vendor-avatar { width: 100px; height: 100px; border: 4px solid var(--ui-border-light); margin: 0 auto 15px; display: block; }
        .gold-display { font-size: 1.2rem; margin-bottom: 15px; }
        .gold-display span { font-weight: bold; color: var(--text-highlight); }

        .stats-list { list-style: none; padding: 0; text-align: left; font-size: 0.9rem; line-height: 1.8; }
        .stats-list li { display: flex; justify-content: space-between; }
        .stats-list span { color: var(--text-highlight); }
        
        .item-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 15px; }
        .item-card {
            background: var(--ui-main);
            border: 2px solid var(--ui-border-light);
            padding: 15px; text-align: center;
            display: flex; flex-direction: column;
            transition: all 0.2s;
        }
        .item-card:hover { background: var(--ui-border-dark); }
        
        .item-icon { font-size: 2rem; margin-bottom: 10px; }
        .item-name { font-size: 0.9rem; margin-bottom: 8px; }
        .rarity-tag { font-size: 0.7rem; padding: 2px 4px; display: inline-block; margin-bottom: 8px; border: 1px solid; }
        .rarity-common { color: var(--rarity-common); border-color: var(--rarity-common); }
        .rarity-uncommon { color: var(--rarity-uncommon); border-color: var(--rarity-uncommon); }
        .rarity-rare { color: var(--rarity-rare); border-color: var(--rarity-rare); }

        .item-card p { font-size: 0.8rem; line-height: 1.4; margin-bottom: 10px; flex-grow: 1; color: var(--ui-border-light); }
        .item-cost { font-weight: 700; color: var(--text-highlight); }

        .buy-btn {
            background: var(--ui-border-light); color: var(--bg-dark);
            border: 2px solid var(--ui-border-dark); padding: 8px;
            font-size: 0.9rem; transition: all 0.1s; cursor: inherit;
            font-family: 'Press Start 2P', cursive;
            margin-top: auto;
        }
        .buy-btn:hover:not(:disabled) { background: var(--text-highlight); }
        .buy-btn:disabled { opacity: 0.5; cursor: not-allowed; }
        
        .message-area {
            margin-top: 20px; padding: 15px;
            background: var(--bg-dark);
            border: 2px solid var(--ui-border-light);
            min-height: 2rem;
            text-align: center; font-size: 0.9rem;
            opacity: 0; transition: opacity 0.5s;
        }
        .message-area.visible { opacity: 1; }

        .btn-proceed {
            margin-top: 30px; display: block;
            width: 100%;
        }
        
        @keyframes fadeIn { to { opacity: 1; } }
    </style>
</head>
<body>
    <div class="overlay"></div>
    <main class="shop-container">
        <header class="shop-header">
            <h1>CARAVANA MÁGICA</h1>
            <p>"SOBREVIVEU, HERÓI? MEUS ITENS PODEM SER A DIFERENÇA ENTRE A GLÓRIA E O ESQUECIMENTO. ESCOLHA COM SABEDORIA."</p>
        </header>

        <div class="shop-layout">
            <aside class="character-panel panel">
                <img src="{{ asset($character->avatar) }}" alt="Avatar do Herói" class="vendor-avatar">
                <h3>{{ $character->name }}</h3>
                <div class="gold-display">OURO: <span id="playerGold">{{ $character->gold }}</span></div>
                <ul class="stats-list">
                    <li>HP: <span id="stat-hp">{{ $character->hp }}</span></li>
                    <li>MP: <span id="stat-mp">{{ $character->mp }}</span></li>
                    <li>ATQ: <span id="stat-attack">{{ $character->attack }}</span></li>
                    <li>DEF: <span id="stat-defense">{{ $character->defense }}</span></li>
                    <li>POÇÕES: <span id="stat-potions">{{ $character->potions }}</span></li>
                </ul>
            </aside>

            <section class="items-panel panel">
                <h3>MERCADORIAS</h3>
                <div class="item-grid">
                    <div class="item-card">
                        <div class="item-name"><span class="rarity-tag rarity-common">COMUM</span></div>
                        <h4 class="item-name">POÇÃO DE VIDA</h4>
                        <p>+1 POÇÃO</p>
                        <p class="item-cost">50 OURO</p>
                        <button class="buy-btn" data-item-id="potion">COMPRAR</button>
                    </div>
                    <div class="item-card">
                        <div class="item-name"><span class="rarity-tag rarity-uncommon">INCOMUM</span></div>
                        <h4 class="item-name">ELIXIR DE MANA</h4>
                        <p>+50 MP (Recupera)</p>
                        <p class="item-cost">75 OURO</p>
                        <button class="buy-btn" data-item-id="elixir">COMPRAR</button>
                    </div>
                    <div class="item-card">
                        <div class="item-name"><span class="rarity-tag rarity-common">COMUM</span></div>
                        <h4 class="item-name">ESPADA DE FERRO</h4>
                        <p>+5 ATAQUE</p>
                        <p class="item-cost">150 OURO</p>
                        <button class="buy-btn" data-item-id="sword1">COMPRAR</button>
                    </div>
                    <div class="item-card">
                        <div class="item-name"><span class="rarity-tag rarity-common">COMUM</span></div>
                        <h4 class="item-name">ESCUDO DE MADEIRA</h4>
                        <p>+5 DEFESA</p>
                        <p class="item-cost">120 OURO</p>
                        <button class="buy-btn" data-item-id="shield1">COMPRAR</button>
                    </div>
                </div>
            </section>
        </div>

        <div class="message-area" id="messageArea"></div>
        
        <a href="{{ route('character.' . $next_stage, $character->id) }}" class="btn btn-proceed">PROSSEGUIR</a>
    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buyButtons = document.querySelectorAll('.buy-btn');
            const goldDisplay = document.getElementById('playerGold');
            const messageArea = document.getElementById('messageArea');
            const characterStats = {
                hp: document.getElementById('stat-hp'), mp: document.getElementById('stat-mp'),
                attack: document.getElementById('stat-attack'), defense: document.getElementById('stat-defense'),
                potions: document.getElementById('stat-potions'),
            };

            const updateStatsUI = (newStats) => {
                if (!newStats) return;
                characterStats.hp.textContent = newStats.hp;
                characterStats.mp.textContent = newStats.mp;
                characterStats.attack.textContent = newStats.attack;
                characterStats.defense.textContent = newStats.defense;
                characterStats.potions.textContent = newStats.potions;
            };

            buyButtons.forEach(button => {
                button.addEventListener('click', async () => {
                    const itemId = button.dataset.itemId;
                    button.disabled = true;
                    button.textContent = '...';
                    
                    try {
                        const response = await fetch("{{ route('character.shop.buy', $character->id) }}", {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                            },
                            body: JSON.stringify({ itemId: itemId })
                        });
                        const data = await response.json();

                        messageArea.classList.add('visible');
                        messageArea.textContent = data.message;
                        messageArea.style.color = data.success ? 'var(--success-color)' : 'var(--error-color)';

                        if(data.success) {
                            goldDisplay.textContent = data.newGold;
                            updateStatsUI(data.newStats);
                        }
                    } catch (error) {
                        messageArea.classList.add('visible');
                        messageArea.textContent = 'ERRO DE CONEXÃO.';
                        console.error('Erro ao comprar:', error);
                    } finally {
                        setTimeout(() => {
                             button.disabled = false;
                             button.textContent = 'COMPRAR';
                        }, 500);
                    }
                });
            });
        });
    </script>
</body>
</html>